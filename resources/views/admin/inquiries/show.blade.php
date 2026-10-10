@extends('admin.layouts.app')
@section('title', 'Request details · DoctorPortfolio Admin')
@section('heading', $definition['label'])
@section('content')
<x-admin.breadcrumbs :items="[['label'=>'Dashboard','url'=>route('admin.dashboard')],['label'=>$definition['label'],'url'=>route($definition['route'].'.index')],['label'=>'Request details']]"/>
<div class="mb-6 flex flex-wrap items-start justify-between gap-4"><div><h1 class="text-2xl font-semibold tracking-tight">{{ ucfirst($definition['singular']) }}</h1><p class="mt-2 text-sm text-slate-500">Private submitted record. Details are read-only; handling changes require an explicit save.</p></div><x-admin.inquiry-status :status="$inquiry->status"/></div>
<x-admin.validation-summary/>
<div class="grid items-start gap-6 xl:grid-cols-2">
    <section class="admin-card p-6"><h2 class="text-base font-semibold">Submitted details</h2><dl class="mt-5 grid gap-4 text-sm">
        <div><dt class="text-xs font-medium text-slate-500">Reference</dt><dd class="mt-1 break-all font-mono">{{ $inquiry->public_reference }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-500">Name</dt><dd class="mt-1 break-words" lang="{{ $inquiry->locale }}">{{ $inquiry->name }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-500">Phone</dt><dd class="mt-1 break-words">{{ $inquiry->phone }}</dd></div>
        @if($inquiry->email)<div><dt class="text-xs font-medium text-slate-500">Email (if supplied)</dt><dd class="mt-1 break-words">{{ $inquiry->email }}</dd></div>@endif
        <div><dt class="text-xs font-medium text-slate-500">Submitted language</dt><dd class="mt-1">{{ $inquiry->locale==='bn' ? 'বাংলা (bn)' : 'English (en)' }}</dd></div>
        <div><dt class="text-xs font-medium text-slate-500">Received</dt><dd class="mt-1">{{ $inquiry->created_at->format('Y-m-d H:i:s') }} · {{ config('app.timezone') }}</dd></div>
        @if($definition['kind']==='appointment')<div><dt class="text-xs font-medium text-slate-500">Consultation at submission</dt><dd class="mt-1"><p>{{ $localized->resolve($inquiry->consultation_label,'en') }}</p><p class="mt-2" lang="bn">{{ $localized->resolve($inquiry->consultation_label,'bn') }}</p><p class="mt-2 text-xs text-slate-400">Stable key: {{ $inquiry->consultation_key }}</p></dd></div>@endif
        <div><dt class="text-xs font-medium text-slate-500">Message</dt><dd class="mt-1 whitespace-pre-wrap break-words" lang="{{ $inquiry->locale }}">{{ $inquiry->message ?? 'No message supplied.' }}</dd></div>
    </dl></section>
    <section class="admin-card p-6"><h2 class="text-base font-semibold">Staff handling</h2><p class="mt-2 text-xs leading-6 text-slate-500">No status change sends notifications or changes public content. {{ $definition['kind']==='appointment' ? 'Confirm only after staff have agreed the visit with the person. A manual time is optional.' : 'This is a general message, not an appointment.' }}</p>
        @if($inquiry->status==='new')<form method="post" action="{{ route($definition['route'].'.read',['inquiry'=>$inquiry->id]) }}" class="mt-4">@csrf @method('PATCH')<input type="hidden" name="expected_version" value="{{ $version }}"><x-admin.button variant="secondary">Mark read</x-admin.button></form>@endif
        <form method="post" action="{{ route($definition['route'].'.update',['inquiry'=>$inquiry->id]) }}" class="mt-5 space-y-5" data-prevent-double-submit>@csrf @method('PATCH')<input type="hidden" name="expected_version" value="{{ $version }}">
            <div><label class="admin-label" for="handling-status">Status</label><select class="admin-field" id="handling-status" name="status">@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status',$inquiry->status)===$status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
            <x-admin.textarea name="internal_notes" label="Internal notes" :value="$inquiry->internal_notes ?? ''" maxlength="15000" hint="Private staff notes. Do not share screenshots of submitted data or notes."/>
            @if($definition['kind']==='appointment')<x-admin.input name="confirmed_visit_at" label="Manually confirmed visit time (optional)" type="datetime-local" :value="$inquiry->confirmed_visit_at?->format('Y-m-d\TH:i') ?? ''" hint="Asia/Dhaka. Allowed for confirmed, completed or cancelled requests. Clear it to return to an unconfirmed status. This does not create a booking or send a notification."/>@endif
            <label class="flex items-start gap-3 text-xs leading-6 text-slate-600"><input class="mt-1 h-4 w-4 shrink-0 accent-jade-700" type="checkbox" name="confirm_status" value="1">I confirm this change if confirming, cancelling, marking spam, or reopening a closed request.</label>
            <x-admin.button>Save handling</x-admin.button>
        </form>
        <p class="mt-6 border-t border-slate-100 pt-4 text-xs leading-6 text-slate-500">Last handled by {{ $inquiry->handledBy?->name ?? ($inquiry->handled_at ? 'Staff account removed' : 'Not yet handled') }}@if($inquiry->handled_at) · {{ $inquiry->handled_at->format('Y-m-d H:i:s') }}@endif</p>
    </section>
</div>
<section class="admin-card mt-6 overflow-hidden p-0"><div class="p-6"><h2 class="text-base font-semibold">Status history</h2><p class="mt-2 text-xs text-slate-500">Creation establishes New at the received time. Only subsequent status changes are recorded here.</p></div>
@if($inquiry->statusChanges->isEmpty())<x-admin.empty-state title="No status changes yet" description="Viewing this request does not change its status." icon="appointments"/>
@else<x-admin.table :headers="['Change','Staff member','Time']" caption="Private status history">@foreach($inquiry->statusChanges as $change)<tr><td class="px-6 py-4">{{ ucfirst($change->from_status) }} → {{ ucfirst($change->to_status) }}</td><td class="px-6 py-4">{{ $change->actor?->name ?? 'Staff account removed' }}</td><td class="px-6 py-4 text-xs text-slate-500">{{ $change->created_at->format('Y-m-d H:i:s') }}</td></tr>@endforeach</x-admin.table>@endif</section>
<section class="admin-card mt-6 border-rose-100 p-6"><h2 class="text-base font-semibold text-rose-800">Permanent deletion</h2><p class="mb-5 mt-2 text-xs leading-6 text-slate-500">Deletes only this active database record and its local status history. Existing backups are not changed. No content, consultation option, shared image or staff account is deleted.</p><x-admin.delete-confirmation :action="route($definition['route'].'.destroy',['inquiry'=>$inquiry->id])" label="request" id="delete-inquiry" :version="$version" description="Delete this active request and its local status history permanently? Existing backups are not changed."/></section>
@endsection
