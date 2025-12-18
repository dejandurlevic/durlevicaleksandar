<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class InquiryController extends Controller
{
    /**
     * Handle incoming subscription inquiry form submissions.
     */
    public function send(Request $request)
    {
        // Rate limiting: max 3 inquiries per hour per IP
        $key = 'inquiry:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }
        RateLimiter::hit($key, 3600); // 1 hour

        try {
            $validated = $request->validate([
                'plan' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'name' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:5000',
            ]);

            // Store inquiry in database
            $inquiry = Inquiry::create([
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'plan' => $validated['plan'],
                'message' => $validated['message'] ?? null,
                'approved' => false,
            ]);

            // Attempt to send email to admin
            try {
                $emailData = [
                    'plan' => $validated['plan'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'name' => $validated['name'] ?? null,
                    'inquiryMessage' => $validated['message'] ?? null,
                    'inquiryId' => $inquiry->id,
                ];
                
                $recipientEmail = config('mail.from.address', 'durlevicaleksandar@gmail.com');
                
                Mail::send('emails.inquiry', $emailData, function ($mail) use ($validated, $recipientEmail) {
                    $mail->to($recipientEmail)
                        ->subject('New Subscription Inquiry: ' . $validated['plan']);
                });
                
                Log::info('Inquiry email sent successfully', [
                    'inquiry_id' => $inquiry->id,
                    'recipient' => $recipientEmail,
                    'from_email' => $validated['email'],
                ]);
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                Log::error('Failed to send inquiry email', [
                    'inquiry_id' => $inquiry->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Your request has been sent. You will be contacted shortly.'
            ]);
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
                'message' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }
}


