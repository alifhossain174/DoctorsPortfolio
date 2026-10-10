<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PasswordRequest;
use App\Http\Requests\Admin\ProfileRequest;
use Illuminate\Support\Str;

final class ProfileController extends AdminController
{
    public function edit()
    {
        return view('admin.profile');
    }

    public function update(ProfileRequest $request)
    {
        $request->user()->update($request->safe()->only(['name', 'email']));

        return redirect()->route('admin.profile.edit')->with('status', 'Your profile has been updated.');
    }

    public function password(PasswordRequest $request)
    {
        // The User hashed cast handles storage. auth.session expires stale sessions.
        $request->user()->forceFill(['password' => $request->validated('password'), 'remember_token' => Str::random(60)])->save();
        $request->session()->regenerate();

        return redirect()->route('admin.profile.edit')->with('status', 'Your password has been changed. Other sessions will be signed out when they next access the admin.');
    }
}
