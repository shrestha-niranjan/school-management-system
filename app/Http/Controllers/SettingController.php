<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\SchoolSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\SettingUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SettingController extends Controller
{
    /**
     * Display the user's setting form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Setting/Edit', [
            'title' => 'Basic Settings'
        ]);
    }

    public function update(SettingUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $schoolSetting = SchoolSetting::first();

        $schoolSetting->update($data);

        return Redirect::route('settings.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
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
