<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
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
        try {
            $registrationUrl = route('register', ['token' => $token]);
            
            Mail::send('emails.inquiry-approved', [
                'name' => $inquiry->name ?? 'there',
                'plan' => $inquiry->plan,
                'registrationUrl' => $registrationUrl,
            ], function ($mail) use ($inquiry) {
                $mail->to($inquiry->email)
                    ->subject('Your Registration Invitation - FitCoachAleksandar');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send approval email', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('admin.inquiries.index')
                ->with('error', 'Inquiry approved but failed to send email. Token: ' . $token);
        }

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry approved and invitation email sent successfully.');
    }
}
