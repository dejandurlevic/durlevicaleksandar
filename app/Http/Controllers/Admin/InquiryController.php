<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class InquiryController extends Controller
{
    /**
     * Display a listing of inquiries.
     */
    public function index()
    {
        try {
            $inquiries = Inquiry::latest()->paginate(15);
            return view('admin.inquiries.index', compact('inquiries'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Failed to load inquiries: ' . $e->getMessage());
        }
    }

    /**
     * Approve an inquiry and generate registration link.
     */
    public function approve(Request $request, Inquiry $inquiry)
    {
        try {
            // Check if already approved
            if ($inquiry->approved) {
                return redirect()->route('admin.inquiries.index')
                    ->with('error', 'This inquiry has already been approved.');
            }

            // Generate unique invite token
            $token = Str::random(64);
            
            // Update inquiry
            $inquiry->update([
                'approved' => true,
                'approved_at' => now(),
                'invite_token' => $token,
                'invite_token_expires_at' => now()->addDays(7), // Token valid for 7 days
            ]);

            // Generate registration URL with token and email as query parameters
            $registrationUrl = route('register') . '?token=' . $token . '&email=' . urlencode($inquiry->email);

            // Store registration URL in session for display on the page
            session()->flash('registration_url', $registrationUrl);
            session()->flash('approved_inquiry_id', $inquiry->id);

            return redirect()->route('admin.inquiries.index')
                ->with('success', 'Inquiry approved successfully. Registration link generated.');
        } catch (\Exception $e) {
            return redirect()->route('admin.inquiries.index')
                ->with('error', 'Failed to approve inquiry: ' . $e->getMessage());
        }
    }
}
