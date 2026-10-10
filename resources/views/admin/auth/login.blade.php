@extends('admin.layouts.guest')
@section('title', 'Sign in')
@section('content')
<div class="mb-8"><span class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-jade-200 bg-jade-50 text-jade-700"><x-admin.icon name="lock"/></span><h2 class="text-3xl font-semibold tracking-tight">Welcome back</h2><p class="mt-3 text-sm leading-6 text-slate-500">Sign in to manage your website workspace.</p></div>
@include('admin.partials.flash')
<x-admin.validation-summary/>
<form method="post" action="{{ route('admin.login.store') }}" class="space-y-5" data-prevent-double-submit>@csrf
    <x-admin.input name="email" label="Email address" type="email" autocomplete="username" maxlength="255" required autofocus/>
    <x-admin.input name="password" label="Password" type="password" autocomplete="current-password" required/>
    <x-admin.button class="w-full">Sign in <x-admin.icon name="arrow" class="h-4 w-4"/></x-admin.button>
</form>
<p class="mt-8 border-t border-slate-200 pt-6 text-xs leading-5 text-slate-500">Administrator access only. Ask the website owner if you need access.</p><a class="mt-5 inline-flex items-center gap-2 text-xs font-medium text-jade-700 hover:text-jade-900" href="{{ route('home') }}">← Return to website</a>
@endsection
