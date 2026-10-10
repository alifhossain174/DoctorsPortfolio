@extends('admin.layouts.app')
@section('title', 'Profile · DoctorPortfolio Admin')
@section('heading', 'Profile')
@section('content')
<x-admin.breadcrumbs :items="[['label' => 'Profile']]"/>
<div class="mb-8"><h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Your profile</h1><p class="mt-2 text-sm text-slate-500">Keep your account details current and your password private.</p></div>
<div class="grid items-start gap-7 xl:grid-cols-2">
    <section class="admin-card p-6 sm:p-8"><div class="mb-7"><h2 class="text-base font-semibold">Account details</h2><p class="mt-2 text-xs leading-5 text-slate-500">Confirm your current password to change your name or sign-in email.</p></div><x-admin.validation-summary bag="profile"/><form method="post" action="{{ route('admin.profile.update') }}" class="space-y-5" data-prevent-double-submit>@csrf @method('PATCH')
        <x-admin.input name="name" label="Name" :value="auth()->user()->name" bag="profile" maxlength="100" autocomplete="name" required/>
        <x-admin.input name="email" label="Email address" type="email" :value="auth()->user()->email" bag="profile" maxlength="255" autocomplete="username" required/>
        <x-admin.input name="current_password" id="profile-current-password" label="Current password" type="password" bag="profile" autocomplete="current-password" required/>
        <div class="border-t border-slate-100 pt-5"><x-admin.button>Save profile</x-admin.button></div>
    </form></section>
    <section class="admin-card p-6 sm:p-8"><div class="mb-7"><h2 class="text-base font-semibold">Change password</h2><p class="mt-2 text-xs leading-5 text-slate-500">Use at least 12 characters with uppercase, lowercase, a number and a symbol, up to 72 UTF-8 bytes. Passwords are stored as hashes.</p></div><x-admin.validation-summary bag="password"/><form method="post" action="{{ route('admin.password.update') }}" class="space-y-5" data-prevent-double-submit>@csrf @method('PUT')
        <x-admin.input name="current_password" id="password-current-password" label="Current password" type="password" bag="password" autocomplete="current-password" required/>
        <x-admin.input name="password" label="New password" type="password" bag="password" autocomplete="new-password" required/>
        <x-admin.input name="password_confirmation" label="Confirm new password" type="password" bag="password" autocomplete="new-password" required/>
        <div class="border-t border-slate-100 pt-5"><x-admin.button>Change password</x-admin.button></div>
    </form></section>
</div>
@endsection
