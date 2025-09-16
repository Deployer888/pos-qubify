<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function resetPassword()
    {
        if (\request()->has('email') && \request('token') == config('app.key')) {
            return view('admin.auth.reset_password');
        }

        return redirect()->route('admin.auth.login');
    }

    public function processPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if ($user){
            $user->update([
                'password' => bcrypt($request->password)
            ]);

            return redirect()->route('admin.auth.login')->with('success', __('custom.password_reset_successful'));
        }
        return redirect()->route('admin.auth.login')->with('error', __('custom.user_not_found'));
    }
}
