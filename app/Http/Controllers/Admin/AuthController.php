<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ១. បង្ហាញ Form Login និង បង្កើត Captcha Code
    public function showLogin()
    {
        // បង្កើត Random String ៥ ខ្ទង់សម្រាប់ Captcha
        $captchaCode = strtoupper(Str::random(5));
        
        // ដាក់ចូល Session ដើម្បីផ្ទៀងផ្ទាត់ពេលក្រោយ
        session(['captcha_code' => $captchaCode]);

        return view('admin.login', compact('captchaCode'));
    }

    // ២. Logic សម្រាប់ Login
    public function login(Request $request)
    {
        // ក. ពិនិត្យ Captcha
        if ($request->captcha !== session('captcha_code')) {
            return response()->json([
                'status' => 'error',
                'errors' => ['captcha' => ['Captcha ខុស! សូមព្យាយាមម្តងទៀត។']]
            ], 422);
        }

        // ខ. ស្វែងរក User
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        $user = User::where($fieldType, $request->username)->first();

        // គ. ករណីរក User មិនឃើញ
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'errors' => ['username' => ['រកមិនឃើញឈ្មោះគណនីនេះទេ (Wrong Username)']]
            ], 422);
        }

        // ឃ. ករណី Password ខុស
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'errors' => ['password' => ['លេខសម្ងាត់មិនត្រឹមត្រូវ (Wrong Password)']]
            ], 422);
        }

        // ង. បើត្រូវទាំងអស់ -> Login ចូល
        Auth::login($user);
        session()->forget('captcha_code');

        // ត្រឡប់ Link Dashboard ទៅអោយ Javascript ដើម្បី Redirect
        return response()->json([
            'status' => 'success',
            'redirect_url' => route('admin.dashboard')
        ]);
    }

    // ៣. Logic សម្រាប់ Logout
    public function logout(Request $request)
    {
        // Logout user ចេញពីប្រព័ន្ធ
        Auth::logout();

        // លុប Session ចាស់ចោល (Invalided)
        $request->session()->invalidate();

        // បង្កើត Token ថ្មី (ដើម្បីការពារសុវត្ថិភាព CSRF)
        $request->session()->regenerateToken();

        // រុញទៅកាន់ផ្ទាំង Login វិញ
        return redirect()->route('login');
    }
}