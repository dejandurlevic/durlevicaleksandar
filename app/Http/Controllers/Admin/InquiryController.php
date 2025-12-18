<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Mail\InquiryApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    /**
     * Display a listing of inquiries.
     */
    public function index()
    {
        $inquiries = Inquiry::latest()->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Approve an inquiry and send invite email.
     */
    public function approve(Inquiry $inquiry)
    {
        if ($inquiry->approved) {
            return redirect()->route('admin.inquiries.index')
                ->with('error', 'This inquiry has already been approved.');
        }

        // Generate invite token
        $token = $inquiry->generateInviteToken();

        // Update inquiry
        $inquiry->update([
            'approved' => true,
            'approved_at' => now(),
        ]);

        // Send approval email with registration link
        $registrationUrl = route('register', ['token' => $token]);
        
        try {
            // Log before sending
            Log::info('Attempting to send approval email', [
                'inquiry_id' => $inquiry->id,
                'email' => $inquiry->email,
                'token' => $token,
            ]);

            Mail::to($inquiry->email)->send(new InquiryApprovedMail(
                $inquiry->name ?? 'there',
                $inquiry->plan,
                $registrationUrl
            ));

            // Log after sending (if no exception was thrown)
            Log::info('Approval email sent successfully', [
                'inquiry_id' => $inquiry->id,
                'email' => $inquiry->email,
            ]);

            return redirect()->route('admin.inquiries.index')
                ->with('success', 'Inquiry approved and invitation email sent successfully. Registration link: ' . $registrationUrl);
                
        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('Failed to send approval email', [
                'inquiry_id' => $inquiry->id,
                'email' => $inquiry->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Check for specific SMTP errors
            $errorMessage = $e->getMessage();
            $userFriendlyMessage = 'Inquiry approved but failed to send email. ';
            
            if (strpos($errorMessage, 'Failed to authenticate') !== false || 
                strpos($errorMessage, 'BadCredentials') !== false ||
                strpos($errorMessage, 'Username and Password not accepted') !== false) {
                $userFriendlyMessage .= 'SMTP authentication failed. Please check your email configuration. ';
            } elseif (strpos($errorMessage, 'Connection') !== false || strpos($errorMessage, 'timeout') !== false) {
                $userFriendlyMessage .= 'Could not connect to email server. ';
            }
            
            $userFriendlyMessage .= 'Registration token: ' . $token . ' | URL: ' . $registrationUrl;
            
            return redirect()->route('admin.inquiries.index')
                ->with('error', $userFriendlyMessage);
        }
    }
}
