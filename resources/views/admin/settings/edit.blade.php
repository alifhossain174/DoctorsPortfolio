@extends('admin.layouts.app')
@section('title','Website settings · DoctorPortfolio')
@section('heading','Website settings')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Website settings']]"/>
<div class="mb-7 flex flex-wrap items-end justify-between gap-4"><div><h1 class="text-3xl font-semibold tracking-tight">Website settings</h1><p class="mt-2 text-sm text-slate-500">Shared sources update every place they are displayed.</p></div><x-admin.button variant="secondary" :href="route('home',['locale'=>'en'])" target="_blank" rel="noopener">View page ↗</x-admin.button></div>
<div class="grid items-start gap-6 lg:grid-cols-4">
    <nav class="admin-card flex flex-wrap gap-1 lg:col-span-1 lg:flex-col" aria-label="Website setting groups">@foreach($groups as $key=>$definition)<a href="{{ route('admin.settings.edit',['group'=>$key]) }}" @class(['rounded-xl px-3 py-2.5 text-sm font-medium','bg-jade-50 text-jade-800'=>$group===$key,'text-slate-500 hover:bg-slate-50'=>$group!==$key]) @if($group===$key) aria-current="page" @endif>{{ $definition['label'] }}</a>@endforeach</nav>
    <div class="lg:col-span-3"><form method="post" action="{{ route('admin.settings.update',$group) }}" class="space-y-6" data-prevent-double-submit>@csrf @method('PUT')
        <x-admin.validation-summary/>
        <section class="admin-card space-y-7"><div><p class="text-xs font-semibold uppercase tracking-widest text-jade-700">Shared content</p><h2 class="mt-2 text-xl font-semibold">{{ $groups[$group]['label'] }}</h2><p class="mt-2 text-sm leading-6 text-slate-500">{{ $groups[$group]['help'] }}</p></div>
        @foreach($groups[$group]['settings'] as $field)
            <x-admin.cms-field :name="'settings['.$field.']'" :label="$editor->label($field)" :definition="[...$settingDefinitions[$field],'multiline'=>in_array($field,['address','consultation_hours','friday_note'],true)]" :value="$settings->getAttribute($field)" :hint="$editor->help($field,$settingDefinitions[$field])" :media-choices="$mediaChoices" :selected="$field==='portrait_media_asset_id' ? $settings->portrait : null" :routes="$field==='cta_target' ? ['contact'] : null"/>
        @endforeach
        @include('admin.pages.content-fields')
        @if($group==='topbar')<div class="rounded-xl bg-slate-50 p-5 text-sm leading-7 text-slate-600"><p><strong>Address:</strong> {{ $settings->localized('address','en') }}</p><p><strong>Hours:</strong> {{ $settings->localized('consultation_hours','en') }}</p><p><strong>Friday:</strong> {{ $settings->localized('friday_note','en') }}</p><a href="{{ route('admin.settings.edit',['group'=>'clinic']) }}" class="mt-3 inline-block font-semibold text-jade-700">Edit shared clinic source →</a><p class="mt-2 text-xs">Location/clock glyphs and topbar structure are fixed design code.</p></div>@endif
        @if($group==='branding')<p class="text-sm leading-6 text-slate-500">Secondary images and their alternative text are page-owned slots: <a class="font-semibold text-jade-700" href="{{ route('admin.pages.edit','home') }}">Home doctor image →</a> · <a class="font-semibold text-jade-700" href="{{ route('admin.pages.edit','about') }}">About profile image →</a></p>@endif
        @if($group==='footer')<a class="text-sm font-semibold text-jade-700" href="{{ route('admin.navigation.index',['placement'=>'footer']) }}">Edit footer navigation →</a>@endif
        @if($group==='notices')<a class="text-sm font-semibold text-jade-700" href="{{ route('admin.pages.edit','diagnostics') }}">Edit page-specific diagnostic notice →</a>@endif
        </section>
        @if($groups[$group]['settings'] || $fields->isNotEmpty())<div class="flex flex-wrap items-center gap-4"><x-admin.button>Save {{ $groups[$group]['label'] }}</x-admin.button><span class="text-xs text-slate-500">Only this group is saved. Both translations remain in one form.</span></div>@endif
    </form></div>
</div>
@endsection
