@extends('admin.layouts.app')
@section('title', 'Dashboard · DoctorPortfolio Admin')
@section('heading', 'Dashboard')
@section('content')
<x-admin.breadcrumbs :items="[['label' => 'Dashboard']]"/>
<div class="mb-8 flex flex-wrap items-start justify-between gap-4"><div><h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Your website, at a glance</h1><p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Welcome, {{ auth()->user()->name }}. This is the current state of your published content and new requests.</p></div><x-admin.badge tone="success"><span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-jade-600"></span>Admin workspace ready</x-admin.badge></div>
<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ([['Published articles', $publishedPosts, 'articles', 'Currently available on the public site'], ['Visible gallery images', $visibleImages, 'gallery', 'Gallery entries marked visible'], ['New appointment requests', $newAppointments, 'appointments', 'Requests, not confirmed bookings'], ['New contact messages', $newMessages, 'messages', 'General messages awaiting review']] as [$metricLabel, $metricValue, $metricIcon, $metricHint])
    <div class="admin-card p-6"><div class="mb-5 flex items-center justify-between gap-3"><p class="text-xs font-medium text-slate-500">{{ $metricLabel }}</p><span class="rounded-xl bg-jade-50 p-2.5 text-jade-700"><x-admin.icon :name="$metricIcon"/></span></div><p class="text-3xl font-semibold tracking-tight">{{ number_format($metricValue) }}</p><p class="mt-3 text-xs leading-5 text-slate-400">{{ $metricHint }}</p></div>
    @endforeach
</div>
<div class="mt-7 grid items-start gap-7 xl:grid-cols-3">
    <section class="admin-card overflow-hidden p-0 xl:col-span-2"><div class="flex flex-wrap items-start justify-between gap-4 p-6"><div><h2 class="text-base font-semibold">Recent inquiries</h2><p class="mt-1 text-xs leading-5 text-slate-500">Most recently received requests across both private inboxes. Opening them does not change status.</p></div><div class="flex gap-4 text-xs font-semibold text-jade-700"><a href="{{ route('admin.appointments.index') }}">Appointments</a><a href="{{ route('admin.contact-messages.index') }}">Contact Messages</a></div></div>
        @if($recentInquiries->isEmpty())<x-admin.empty-state title="No inquiries received yet" description="Saved public appointment requests and general messages will appear here. No example records are included." icon="messages"/>
        @else<x-admin.table :headers="['Request','Kind / status','Received','Details']" caption="Recent private inquiries">@foreach($recentInquiries as $inquiry)<tr>
            <th scope="row" class="max-w-xs px-6 py-4 text-left"><p class="break-all font-mono text-xs text-slate-500">{{ $inquiry->public_reference }}</p><p class="mt-2 break-words font-semibold" lang="{{ $inquiry->locale }}">{{ $inquiry->name }}</p></th>
            <td class="px-6 py-4"><p class="mb-2 text-xs text-slate-500">{{ $inquiry->kind==='appointment' ? 'Appointment request' : 'General contact' }}</p><x-admin.inquiry-status :status="$inquiry->status"/></td><td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">{{ $inquiry->created_at->format('Y-m-d H:i') }}</td>
            <td class="px-6 py-4"><a class="text-sm font-semibold text-jade-700 hover:underline" href="{{ route('admin.'.($inquiry->kind==='appointment' ? 'appointments' : 'contact-messages').'.show',['inquiry'=>$inquiry->id]) }}">Open request</a></td>
        </tr>@endforeach</x-admin.table>@endif
    </section>
    <section class="admin-card p-6"><h2 class="text-base font-semibold">Quick links</h2><p class="mt-2 text-xs leading-6 text-slate-500">Edit website content or open the public site in either language.</p><div class="mt-5 grid gap-3 text-sm font-semibold text-jade-700">
        <a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('admin.settings.edit') }}">Website Settings</a><a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('admin.pages.index') }}">Page Content · {{ $pageCount }} page types</a><a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('admin.articles.index') }}">Articles</a><a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('admin.gallery.index') }}">Gallery</a><a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('admin.media.index') }}">Media · {{ $mediaCount }} records</a>
        <a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('home',['locale'=>'en']) }}" target="_blank" rel="noopener noreferrer">View website · English ↗</a><a class="rounded-xl border border-slate-200 p-3 hover:bg-jade-50" href="{{ route('home',['locale'=>'bn']) }}" target="_blank" rel="noopener noreferrer">View website · বাংলা ↗</a>
    </div></section>
</div>
<p class="mt-6 text-xs leading-6 text-slate-500">All counts come from the current database. Requests are not confirmed bookings. This private workspace sends no email or social notifications, and its pages are not stored in public caches.</p>
@endsection
