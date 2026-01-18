<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    /**
     * Display all inquiries.
     */
    public function index()
    {
        $inquiries = Inquiry::latest()->paginate(15);
        
        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Approve an inquiry and generate invite token.
     */
    public function approve(Request $request, Inquiry $inquiry)
    {
        // Generate unique token
        $token = Str::random(64);
        
        // Update inquiry
        $inquiry->update([
            'approved' => true,
            'approved_at' => now(),
            'invite_token' => $token,
            'invite_token_expires_at' => now()->addDays(7), // Token valid for 7 days
        ]);

        // Send email with token to user
        try {
            $registerUrl = route('register', ['token' => $token]);
            
            Mail::send('emails.registration-invite', [
                'name' => $inquiry->name ?? 'User',
                'plan' => $inquiry->plan,
                'token' => $token,
                'registerUrl' => $registerUrl,
            ], function ($mail) use ($inquiry) {
                $mail->to($inquiry->email)
                    ->subject('Your Registration Invitation - ' . $inquiry->plan);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send registration invite email', [
                'error' => $e->getMessage(),
                'inquiry_id' => $inquiry->id,
            ]);
        }

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry approved and invitation email sent to ' . $inquiry->email);
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}
