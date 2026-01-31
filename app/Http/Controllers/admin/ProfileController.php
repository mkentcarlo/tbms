<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user's profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.profile.index', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.index')->with('message', 'Profile updated successfully.');
    }

    /**
     * Update the user's profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        // Store new profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store in public/uploads/profiles directory
            $destinationPath = public_path('uploads/profiles');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            
            $user->profile_picture = 'uploads/profiles/' . $filename;
            $user->save();
        }

        return redirect()->route('profile.index')->with('message', 'Profile picture updated successfully.');
    }

    /**
     * Remove the user's profile picture.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removePicture()
    {
        $user = Auth::user();

        // Delete profile picture if exists
        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = null;
        $user->save();

        return redirect()->route('profile.index')->with('message', 'Profile picture removed successfully.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('message', 'Password updated successfully.');
    }

    /**
     * Show the settings page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function settings()
    {
        return view('dashboard.profile.settings', [
            'user' => Auth::user(),
            'app_name' => AppSetting::appName(),
            'app_logo' => AppSetting::appLogo(),
            'app_description' => AppSetting::appDescription(),
            'login_background' => AppSetting::loginBackground(),
            // Print document settings
            'country_name' => AppSetting::countryName(),
            'province_name' => AppSetting::provinceName(),
            'municipality_name' => AppSetting::municipalityName(),
            'certifying_officer_name' => AppSetting::certifyingOfficerName(),
            'certifying_officer_title' => AppSetting::certifyingOfficerTitle(),
            'budget_officer_name' => AppSetting::budgetOfficerName(),
            'budget_officer_title' => AppSetting::budgetOfficerTitle(),
        ]);
    }

    /**
     * Update settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_description' => ['nullable', 'string', 'max:500'],
            'app_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'login_background' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            // Print document settings
            'country_name' => ['nullable', 'string', 'max:255'],
            'province_name' => ['nullable', 'string', 'max:255'],
            'municipality_name' => ['nullable', 'string', 'max:255'],
            'certifying_officer_name' => ['nullable', 'string', 'max:255'],
            'certifying_officer_title' => ['nullable', 'string', 'max:255'],
            'budget_officer_name' => ['nullable', 'string', 'max:255'],
            'budget_officer_title' => ['nullable', 'string', 'max:255'],
        ]);

        // Update app name
        AppSetting::set('app_name', $request->app_name);
        
        // Update app description
        AppSetting::set('app_description', $request->app_description);

        // Update print document settings
        if ($request->filled('country_name')) {
            AppSetting::set('country_name', $request->country_name);
        }
        if ($request->filled('province_name')) {
            AppSetting::set('province_name', $request->province_name);
        }
        if ($request->filled('municipality_name')) {
            AppSetting::set('municipality_name', $request->municipality_name);
        }
        if ($request->filled('certifying_officer_name')) {
            AppSetting::set('certifying_officer_name', $request->certifying_officer_name);
        }
        if ($request->filled('certifying_officer_title')) {
            AppSetting::set('certifying_officer_title', $request->certifying_officer_title);
        }
        if ($request->filled('budget_officer_name')) {
            AppSetting::set('budget_officer_name', $request->budget_officer_name);
        }
        if ($request->filled('budget_officer_title')) {
            AppSetting::set('budget_officer_title', $request->budget_officer_title);
        }

        // Ensure uploads directory exists
        $destinationPath = public_path('uploads/settings');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            // Delete old logo if exists
            $oldLogo = AppSetting::appLogo();
            if ($oldLogo && file_exists(public_path($oldLogo))) {
                unlink(public_path($oldLogo));
            }

            $file = $request->file('app_logo');
            $filename = 'app_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            
            AppSetting::set('app_logo', 'uploads/settings/' . $filename);
        }

        // Handle background upload
        if ($request->hasFile('login_background')) {
            // Delete old background if exists
            $oldBackground = AppSetting::loginBackground();
            if ($oldBackground && file_exists(public_path($oldBackground))) {
                unlink(public_path($oldBackground));
            }

            $file = $request->file('login_background');
            $filename = 'login_bg_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            
            AppSetting::set('login_background', 'uploads/settings/' . $filename);
        }

        return redirect()->route('settings.index')->with('message', 'Settings updated successfully.');
    }

    /**
     * Remove app logo.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeLogo()
    {
        $oldLogo = AppSetting::appLogo();
        if ($oldLogo && file_exists(public_path($oldLogo))) {
            unlink(public_path($oldLogo));
        }
        
        AppSetting::set('app_logo', null);

        return redirect()->route('settings.index')->with('message', 'Logo removed successfully.');
    }

    /**
     * Remove login background.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeBackground()
    {
        $oldBackground = AppSetting::loginBackground();
        if ($oldBackground && file_exists(public_path($oldBackground))) {
            unlink(public_path($oldBackground));
        }
        
        AppSetting::set('login_background', null);

        return redirect()->route('settings.index')->with('message', 'Background removed successfully.');
    }
}
