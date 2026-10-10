@extends('admin.layouts.app')
@section('title','Page content · DoctorPortfolio')
@section('heading','Page content')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Page content']]"/>
<div class="mb-7"><h1 class="text-3xl font-semibold tracking-tight">Page content</h1><p class="mt-2 text-sm text-slate-500">Eight fixed layouts. Edit their content and metadata; collections remain in their own modules.</p></div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">@foreach($pages as $page)<section class="admin-card space-y-4"><x-admin.icon name="pages" class="h-7 w-7 text-jade-700"/><div><h2 class="text-lg font-semibold">{{ ucfirst($page->key) }}{{ $page->key==='article' ? ' template' : '' }}</h2><p class="mt-2 text-sm text-slate-500">{{ $page->localized('label','en') }}</p></div><x-admin.button variant="secondary" :href="route('admin.pages.edit',$page)">Edit content</x-admin.button></section>@endforeach</div>
@endsection
