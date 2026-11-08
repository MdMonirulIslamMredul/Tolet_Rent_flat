<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental\UserVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentBDVerificationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $verifications = UserVerification::with('user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('admin.rentbd.verifications.index', compact('verifications', 'status'));
    }

    public function approve(UserVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'reviewer_id' => Auth::id(),
            'verified_at' => now(),
        ]);

        $verification->user->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
        ]);

        return back()->with('success', "ব্যবহারকারী {$verification->user->name}-এর NID ও সেলফি ভেরিফিকেশন সফলভাবে অনুমোদিত হয়েছে!");
    }

    public function reject(Request $request, UserVerification $verification)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $verification->update([
            'status' => 'rejected',
            'reviewer_id' => Auth::id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $verification->user->update([
            'verification_status' => 'rejected',
        ]);

        return back()->with('success', 'ভেরিফিকেশন আবেদন বাতিল করা হয়েছে।');
    }
}

