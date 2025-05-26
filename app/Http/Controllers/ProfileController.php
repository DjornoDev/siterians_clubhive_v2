<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $isEmailChanging = $user->email !== $request->email;

        // If email is changing, ensure we're in the password confirmation form submission
        if ($isEmailChanging && !$request->has('confirm_email_change')) {
            return back()->withInput();
        }

        // Update user data
        $user->fill($request->safe()->except(['profile_picture', 'password', 'confirm_email_change', 'modal_has_profile_picture']));

        // Handle profile picture upload
        if ($request->hasFile('profile_picture') || ($request->has('modal_has_profile_picture') && $request->modal_has_profile_picture == '1')) {
            $file = $request->file('profile_picture');

            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
            }

            // Store the new profile picture
            $fileName = time() . '_' . $user->user_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profile_pictures', $fileName, 'public');

            $user->profile_picture = $fileName;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete user's profile picture if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete('profile_pictures/' . $user->profile_picture);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
