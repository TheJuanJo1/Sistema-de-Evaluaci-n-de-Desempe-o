<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
        // Fill basic fields (name, email, etc.)
        $request->user()->fill($request->validated());

        // Handle signature upload if present
        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $request->user()->signature = $path;
        }

        // Reset email verification if email changed
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect('/profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's signature.
     */
    public function deleteSignature(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->signature && Storage::disk('public')->exists($user->signature)) {
            Storage::disk('public')->delete($user->signature);
        }
        $user->signature = null;
        $user->save();
        return back()->with('status', 'signature-deleted');
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
