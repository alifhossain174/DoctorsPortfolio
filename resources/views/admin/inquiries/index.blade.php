@extends('admin.layouts.app')
@section('title', $definition['label'].' · DoctorPortfolio Admin')
@section('heading', $definition['label'])
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','url'=>route('admin.dashboard')],['label'=>$definition['label']]]"/>
<div class="mb-6"><h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">{{ $definition['label'] }}</h1><p class="mt-2 text-sm leading-6 text-slate-500">Private {{ $definition['singular'] }} inbox. Opening a request does not mark it read. All times use {{ config('app.timezone') }}.</p></div>
<x-admin.validation-summary/>
<form method="post" action="{{ route($definition['route'].'.filter') }}" class="admin-card mb-6 grid gap-4 p-5 sm:grid-cols-2 xl:grid-cols-5">@csrf
    <x-admin.input name="q" label="Search name, reference or phone" :value="$filter['q'] ?? ''" maxlength="100" hint="Search stays in your private session, not in the URL." autocomplete="off"/>
    <div><label class="admin-label" for="inquiry-status">Status</label><select id="inquiry-status" name="status" class="admin-field"><option value="">All statuses</option>@foreach($definition['statuses'] as $status)<option value="{{ $status }}" @selected(($filter['status'] ?? '') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
    <x-admin.input name="from" label="Received from" type="date" :value="$filter['from'] ?? ''"/>
    <x-admin.input name="to" label="Received through" type="date" :value="$filter['to'] ?? ''"/>
    <div class="flex flex-wrap items-end gap-3"><x-admin.button>Apply filters</x-admin.button><x-admin.button variant="secondary" name="reset" value="1">Reset</x-admin.button></div>
</form>
<section class="admin-card overflow-hidden p-0">
@if($inquiries->isEmpty())<x-admin.empty-state :title="'No '.$definition['label'].' found'" description="There are no requests matching these filters. New public submissions appear here after they are saved." icon="messages"/>
@else<x-admin.table :headers="array_merge(['Reference / Name','Phone'], $definition['kind']==='appointment' ? ['Consultation'] : [], ['Status','Received','Details'])" :caption="$definition['label'].' private inbox'">
@foreach($inquiries as $inquiry)<tr>
    <th scope="row" class="max-w-xs px-6 py-4 text-left"><p class="break-all font-mono text-xs text-slate-500">{{ $inquiry->public_reference }}</p><p class="mt-2 break-words font-semibold" lang="{{ $inquiry->locale }}">{{ $inquiry->name }}</p></th>
    <td class="whitespace-nowrap px-6 py-4">{{ $inquiry->phone }}</td>
    @if($definition['kind']==='appointment')<td class="max-w-xs px-6 py-4 text-sm">{{ $localized->resolve($inquiry->consultation_label, 'en') }}</td>@endif
    <td class="px-6 py-4"><x-admin.inquiry-status :status="$inquiry->status"/></td><td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500"><time datetime="{{ $inquiry->created_at->toIso8601String() }}">{{ $inquiry->created_at->format('Y-m-d H:i') }}</time></td>
    <td class="px-6 py-4"><a class="text-sm font-semibold text-jade-700 hover:underline" href="{{ route($definition['route'].'.show', ['inquiry'=>$inquiry->id]) }}">Open request</a></td>
</tr>@endforeach</x-admin.table><x-admin.pagination :paginator="$inquiries"/>
@endif</section>
<p class="mt-4 text-xs leading-6 text-slate-500">Private staff review only. Status changes send no email or social notification. {{ $definition['kind']==='appointment' ? 'An appointment request is not a confirmed booking.' : 'General contact messages have no appointment-confirmation controls.' }}</p>
@endsection
