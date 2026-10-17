@extends('admin.layouts.app')
@section('title', 'Upload image · DoctorPortfolio')
@section('heading', 'Upload image')
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','href'=>route('admin.dashboard')],['label'=>'Media','href'=>route('admin.media.index')],['label'=>'Upload']]"/>
<div class="mb-7"><h1 class="text-3xl font-semibold tracking-tight">Upload an image</h1><p class="mt-2 text-sm text-slate-500">Website images are public. Do not upload patient records or private documents.</p></div>
<form method="post" enctype="multipart/form-data" action="{{ route('admin.media.store') }}" class="max-w-4xl space-y-6" data-prevent-double-submit>@csrf
    <input type="hidden" name="quick_upload" value="1">
    @if($picker)<input type="hidden" name="picker" value="1">@endif
    <x-admin.validation-summary/>
    <div class="admin-card"><x-admin.input name="image" type="file" label="Image file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" required hint="JPEG, PNG or WebP · up to 5 MiB · maximum 6000 pixels per edge and 24 million pixels. Original ratios are retained."/></div>
    <div class="admin-card space-y-7">@include('admin.media.fields', ['media'=>null, 'quickUpload'=>true])</div>
    <div class="flex gap-3"><x-admin.button>Upload image</x-admin.button><x-admin.button variant="secondary" :href="route('admin.media.index')">Cancel</x-admin.button></div>
</form>
@endsection
