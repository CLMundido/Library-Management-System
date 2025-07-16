<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        
        // Mock user statistics
        $stats = [
            'total_borrowed' => 15,
            'currently_borrowed' => 3,
            'total_returned' => 12,
            'overdue_count' => 0,
            'total_fines_paid' => 0,
            'member_since' => $user->created_at ?? now()->subYear()
        ];

        return view('profile', compact('user', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password changed successfully!');
    }

    public function settings()
    {
        $user = Auth::user();
        
        // Mock user preferences
        $preferences = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'due_date_reminders' => true,
            'newsletter_subscription' => true,
            'privacy_settings' => 'public'
        ];

        return view('settings', compact('user', 'preferences'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'due_date_reminders' => 'boolean',
            'newsletter_subscription' => 'boolean',
            'privacy_settings' => 'in:public,private,friends'
        ]);

        // In real implementation, save to user_preferences table
        // UserPreference::updateOrCreate(['user_id' => Auth::id()], $request->all());

        return redirect()->route('settings')
            ->with('success', 'Settings updated successfully!');
    }
}