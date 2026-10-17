<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MediaRequest;
use App\Models\MediaAsset;
use App\Services\AdminContentWriter;
use App\Services\MediaManager;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class MediaController extends AdminController
{
    public function index(Request $request)
    {
        $filter = $request->validate(['q' => ['nullable', 'string', 'max:100'], 'kind' => ['nullable', Rule::in(['local', 'external'])], 'picker' => ['nullable', Rule::in(['1'])], 'page' => ['nullable', 'integer', 'min:1']]);
        $media = MediaAsset::query()->withCount(['posts', 'galleryImages', 'contentFields', 'settings', 'pages', 'ogPosts'])
            ->when($filter['q'] ?? null, fn ($query, $q) => $query->where(function ($query) use ($q) {
                $query->where('title->en', 'like', '%'.$q.'%')->orWhere('title->bn', 'like', '%'.$q.'%');
            }))
            ->when(($filter['kind'] ?? null) === 'local', fn ($query) => $query->whereNotNull('path'))
            ->when(($filter['kind'] ?? null) === 'external', fn ($query) => $query->whereNotNull('source_url'))
            ->latest('id')->paginate(config('media.per_page'))->appends(array_intersect_key($filter, array_flip(['q', 'kind', 'picker'])));

        return view('admin.media.index', ['media' => $media, 'filter' => $filter]);
    }

    public function create(Request $request)
    {
        $context = $request->validate(['picker' => ['sometimes', 'in:1']]);

        return view('admin.media.create', ['picker' => ($context['picker'] ?? null) === '1']);
    }

    public function store(MediaRequest $request, MediaManager $manager)
    {
        $asset = $manager->upload($request->file('image'), $request->safe()->only(MediaRequest::textFields()));

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $asset->id,
                'url' => $asset->url(),
                'title' => $asset->localized('title', 'en'),
                'alt' => $asset->localized('alt_text', 'en'),
            ], 201);
        }

        return redirect()->route('admin.media.edit', ['media' => $asset, ...($request->validated('picker') === '1' ? ['picker' => '1'] : [])])->with('status', 'Image uploaded. It is ready to choose in website content.');
    }

    public function edit(Request $request, MediaAsset $media)
    {
        $context = $request->validate(['picker' => ['sometimes', 'in:1']]);
        $media->load(['posts', 'galleryImages', 'contentFields', 'settings', 'pages', 'ogPosts']);
        $usages = [];
        foreach (['posts' => 'Article', 'galleryImages' => 'Gallery', 'contentFields' => 'Page field', 'settings' => 'Doctor portrait', 'pages' => 'Page OG image', 'ogPosts' => 'Article OG image'] as $relation => $label) {
            foreach ($media->$relation as $record) {
                $usages[] = ['type' => $label, 'identity' => $record->slug ?? $record->key, 'label' => $record->localized('title', 'en') ?: ($record->localized('caption', 'en') ?: ($record->slug ?? $record->key))];
            }
        }

        return view('admin.media.edit', ['media' => $media, 'usages' => $usages, 'picker' => ($context['picker'] ?? null) === '1',
            'mediaChoices' => MediaAsset::whereKeyNot($media->id)->orderBy('id')->get(['id', 'title', 'alt_text', 'path', 'disk', 'source_url'])]);
    }

    public function update(MediaRequest $request, MediaAsset $media, AdminContentWriter $writer)
    {
        try {
            $writer->update($media, $request->safe()->only(MediaRequest::textFields()));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.media.edit', $media));
        } catch (\Throwable) {
            return redirect()->route('admin.media.edit', $media)->withErrors(['media' => 'Details could not be saved. Please retry.'])->withInput($request->safe()->only(MediaRequest::textFields()));
        }

        return redirect()->route('admin.media.edit', ['media' => $media, ...($request->validated('picker') === '1' ? ['picker' => '1'] : [])])->with('status', 'Image details saved.');
    }

    public function replace(Request $request, MediaAsset $media, MediaManager $manager)
    {
        try {
            $data = $request->validate(['replacement_id' => ['required', 'integer', Rule::notIn([$media->id]), 'exists:media_assets,id'], 'confirm_replace' => ['accepted']]);
            $count = $manager->replaceReferences($media, MediaAsset::findOrFail($data['replacement_id']));
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.media.edit', $media));
        } catch (\Throwable) {
            return redirect()->route('admin.media.edit', $media)->withErrors(['media' => 'References could not be replaced. No changes were committed; please retry.']);
        }

        return redirect()->route('admin.media.edit', $media)->with('status', $count.' image references replaced. The original image record and file remain available.');
    }

    public function destroy(Request $request, MediaAsset $media, MediaManager $manager)
    {
        try {
            $request->validate(['confirm_delete' => ['accepted']]);
            $clean = $manager->deleteUnused($media);
        } catch (ValidationException $error) {
            throw $error->redirectTo(route('admin.media.edit', $media));
        }

        return redirect()->route('admin.media.index')->with('status', $clean ? 'Unused image removed.' : 'Image record removed. A temporary image file needs hosting support cleanup; see the media setup guide.');
    }
}
