<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomerForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return back()->withErrors(['email' => 'No customer found with this email address.']);
        }

        $token = Str::random(60);

        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // TODO: Send email with reset link
        // For now, we'll just redirect with the token (in production, send email)
        return redirect()->route('customer.password.reset', ['token' => $token])
            ->with('success', 'Password reset link sent to your email!');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('customer.auth.reset-password', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $resetData = \DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetData || !Hash::check($request->token, $resetData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        $customer = Customer::where('email', $request->email)->first();
        $customer->password = Hash::make($request->password);
        $customer->save();

        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('customer.login')->with('success', 'Password reset successful!');
    }
}
