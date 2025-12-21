<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * Handle incoming subscription inquiry form submissions.
     */
    public function send(Request $request)
    {
        try {
            // Rate limiting
            $key = 'inquiry:' . $request->ip();
            $maxAttempts = 5;
            $decayMinutes = 60;
            
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please try again in ' . ceil($seconds / 60) . ' minutes.'
                ], 429);
            }
            
            \Illuminate\Support\Facades\RateLimiter::hit($key, $decayMinutes * 60);

            $validated = $request->validate([
                'plan' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'name' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:2000',
            ]);

            // Store inquiry in database
            $inquiry = \App\Models\Inquiry::create([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'plan' => $validated['plan'],
                'message' => $validated['message'] ?? null,
                'approved' => false,
            ]);

            // Attempt to send email
            try {
                // Rename 'message' to 'inquiryMessage' to avoid conflict with Laravel's reserved $message variable
                $emailData = [
                    'plan' => $validated['plan'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'name' => $validated['name'] ?? null,
                    'inquiryMessage' => $validated['message'] ?? null,
                    'inquiryId' => $inquiry->id,
                ];
                
                // Get recipient email from config (MAIL_FROM_ADDRESS or fallback)
                $recipientEmail = config('mail.from.address', 'durlevicaleksandar@gmail.com');
                
                Mail::send('emails.inquiry', $emailData, function ($mail) use ($validated, $recipientEmail) {
                    $mail->to($recipientEmail)
                        ->subject('New Subscription Inquiry: ' . $validated['plan']);
                });
                
                Log::info('Inquiry email sent successfully', [
                    'recipient' => $recipientEmail,
                    'from_email' => $validated['email'],
                    'plan' => $validated['plan'],
                    'name' => $validated['name'] ?? 'Not provided',
                    'phone' => $validated['phone']
                ]);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Your request has been sent. You will be contacted shortly.'
                ]);
            } catch (\Exception $e) {
                // Log the detailed error
                Log::error('Failed to send inquiry email', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'email' => $validated['email'] ?? 'unknown',
                    'plan' => $validated['plan'] ?? 'unknown'
                ]);
                
                // Check for specific SMTP authentication errors
                $errorMessage = $e->getMessage();
                $userFriendlyMessage = 'Failed to send inquiry email.';
                
                if (strpos($errorMessage, 'Failed to authenticate') !== false || 
                    strpos($errorMessage, 'BadCredentials') !== false ||
                    strpos($errorMessage, 'Username and Password not accepted') !== false) {
                    $userFriendlyMessage = 'Email authentication failed. Please check your Gmail settings. You need to use an App Password (not your regular password). Enable 2-Step Verification and generate an App Password at: https://myaccount.google.com/apppasswords';
                } elseif (strpos($errorMessage, 'Connection') !== false || strpos($errorMessage, 'timeout') !== false) {
                    $userFriendlyMessage = 'Could not connect to email server. Please check your internet connection and try again.';
                } elseif (strpos($errorMessage, 'Could not instantiate mail function') !== false) {
                    $userFriendlyMessage = 'Email service is not properly configured. Please contact the administrator.';
                }
                
                // Return error response with user-friendly message
                return response()->json([
                    'success' => false,
                    'message' => $userFriendlyMessage,
                    'technical_error' => config('app.debug') ? $errorMessage : null
                ], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Inquiry submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}


