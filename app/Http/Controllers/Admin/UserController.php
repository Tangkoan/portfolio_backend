<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ១. កុំភ្លេចហៅអាមួយនេះមកប្រើ
use App\Models\User; // <--- បន្ថែមបន្ទាត់នេះចូល

class UserController extends Controller
{
    public function userList(){
        return view('admin.user.user_list');
    }

    // 1. បង្ហាញផ្ទាំង User Info
    public function profile()
    {
        $user = Auth::user();
        return view('admin.infouser.profile', compact('user'));
    }

    // 2. រក្សាទុកការកែប្រែ User Info
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ដាក់រូបបានត្រឹម 2MB
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // ២. ដំណើរការប្តូររូប (Logic សំខាន់នៅទីនេះ)
        if ($request->hasFile('avatar')) {

            // ក. ពិនិត្យមើល៖ បើ User មានរូបចាស់ ហើយរូបនោះពិតជាមានក្នុង Server មែន
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                // ខ. លុបរូបចាស់នោះចោលភ្លាម
                Storage::disk('public')->delete($user->avatar);
            }

            // គ. ដាក់រូបថ្មីចូល
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // ឃ. យកឈ្មោះរូបថ្មីទៅត្រៀម Update ចូល Database
            $data['avatar'] = $path;
        }

        // ៣. Update ទិន្នន័យ
        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    // 3. បង្ហាញផ្ទាំង Change Password
    public function password()
    {
        return view('admin.infouser.password');
    }

    // 4. រក្សាទុកការប្ដូរ Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed', // confirmed នឹង check ជាមួយ field password_confirmation
        ]);

        $user = Auth::user();

        // ផ្ទៀងផ្ទាត់ Password ចាស់
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match!']);
        }

        // Update Password ថ្មី
        User::whereId($user->id)->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}