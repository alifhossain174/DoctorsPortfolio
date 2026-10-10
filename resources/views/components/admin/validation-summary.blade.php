@props(['bag' => 'default'])
@if ($errors->getBag($bag)->any())
<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800" role="alert"><p class="font-semibold">Please check the following</p><ul class="mt-2 list-disc space-y-1 pl-5">@foreach ($errors->getBag($bag)->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
