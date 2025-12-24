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

        // ============================================================
        // [បន្ថែមថ្មី]៖ កត់ត្រាសកម្មភាពចូលប្រព័ន្ធ (User Action Log)
        // ============================================================
        activity()
            ->causedBy($user) // កត់ថា "អ្នកណា" ជាអ្នក Login
            ->withProperties([
                'ip' => $request->ip(),            // ចាប់យក IP Address
                'browser' => $request->userAgent() // ចាប់យក Browser (Chrome, Firefox...)
            ])
            ->log('logged in'); // សារដែលចង់បង្ហាញ: "User A logged in"

        // ត្រឡប់ Link Dashboard ទៅអោយ Javascript ដើម្បី Redirect
        return response()->json([
            'status' => 'success',
            'redirect_url' => route('admin.dashboard')
        ]);
    }

    // ៣. Logic សម្រាប់ Logout
    public function logout(Request $request)
    {
        // ១. កត់ត្រាសកម្មភាព (ដូចអ្នកបានធ្វើនៅ Login)
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => $request->ip(),
                    'browser' => $request->userAgent()
                ])
                ->log('logged out');
        }

        // ២. Logout Logic ធម្មតា
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // ៣. [ចំណុចសំខាន់ដែលត្រូវកែ]
        // ជំនួសឱ្យ return redirect(...) យើង return JSON វិញ
        return response()->json([
            'status' => 'success',
            'redirect_url' => route('login') // ផ្ញើ Link Login ទៅឱ្យ Frontend
        ]);
    }
}