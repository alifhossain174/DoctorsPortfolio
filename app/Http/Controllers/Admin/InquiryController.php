<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\InquiryActionRequest;
use App\Models\Inquiry;
use App\Services\InquiryManager;
use App\Services\LocalizedContent;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class InquiryController extends AdminController
{
    private function definition(Request $request): array
    {
        return [...config('inquiries.inboxes.'.$request->route('inbox')), 'route' => 'admin.'.$request->route('inbox')];
    }

    public function index(Request $request)
    {
        $definition = $this->definition($request);
        $page = filter_var($request->query('page', 1), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 999999]]) ?: 1;
        if (array_diff(array_keys($request->query()), ['page'])) {
            return redirect()->route($definition['route'].'.index', $page > 1 ? ['page' => $page] : []);
        }
        $filter = $request->session()->get('inquiry_filters.'.$definition['kind'], []);
        $query = Inquiry::where('kind', $definition['kind']);
        $term = $filter['q'] ?? null;
        if ($term !== null && $term !== '') {
            // Literal substring search: %, _ and ! are escaped, parameters remain bound.
            $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $term).'%';
            $query->where(fn ($query) => $query->whereRaw("name LIKE ? ESCAPE '!'", [$term])->orWhereRaw("phone LIKE ? ESCAPE '!'", [$term])->orWhereRaw("public_reference LIKE ? ESCAPE '!'", [$term]));
        }
        $query->when($filter['status'] ?? null, fn ($query, $status) => $query->where('status', $status));
        if ($filter['from'] ?? null) {
            $query->where('created_at', '>=', CarbonImmutable::parse($filter['from'], config('app.timezone'))->startOfDay());
        }
        if ($filter['to'] ?? null) {
            $query->where('created_at', '<', CarbonImmutable::parse($filter['to'], config('app.timezone'))->addDay()->startOfDay());
        }

        return view('admin.inquiries.index', ['definition' => $definition, 'filter' => $filter, 'inquiries' => $query->orderByDesc('created_at')->orderByDesc('id')->paginate(config('inquiries.per_page'), ['*'], 'page', $page), 'localized' => app(LocalizedContent::class)]);
    }

    public function filter(Request $request)
    {
        $definition = $this->definition($request);
        if ($request->boolean('reset')) {
            $request->session()->forget('inquiry_filters.'.$definition['kind']);
        } else {
            $validator = Validator::make($request->only(['q', 'status', 'from', 'to']), ['q' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in($definition['statuses'])], 'from' => ['nullable', 'date_format:Y-m-d'], 'to' => ['nullable', 'date_format:Y-m-d', ...($request->filled('from') ? ['after_or_equal:from'] : [])]]);
            if ($validator->fails()) {
                return redirect()->route($definition['route'].'.index')->withErrors($validator);
            }
            $request->session()->put('inquiry_filters.'.$definition['kind'], $validator->validated());
        }

        return redirect()->route($definition['route'].'.index');
    }

    public function show(Request $request, string $inquiry, InquiryManager $manager)
    {
        $definition = $this->definition($request);
        $record = Inquiry::where('kind', $definition['kind'])->with(['handledBy', 'statusChanges' => fn ($query) => $query->with('actor')->orderByDesc('created_at')->orderByDesc('id')])->findOrFail($inquiry);

        return view('admin.inquiries.show', ['definition' => $definition, 'inquiry' => $record, 'version' => $manager->version($record), 'statuses' => $manager->availableStatuses($record), 'localized' => app(LocalizedContent::class)]);
    }

    public function update(InquiryActionRequest $request, InquiryManager $manager)
    {
        return $this->write($request, $manager, $request->validated());
    }

    public function read(InquiryActionRequest $request, InquiryManager $manager)
    {
        return $this->write($request, $manager, [...$request->validated(), 'status' => 'read']);
    }

    private function write(InquiryActionRequest $request, InquiryManager $manager, array $data)
    {
        try {
            $manager->update($request->inquiry()->id, $request->inquiry()->kind, $data, $request->user());
        } catch (ValidationException $exception) {
            return redirect($request->destination())->withErrors($exception->errors())->withInput($request->safeInput());
        } catch (\PDOException) {
            return redirect($request->destination())->withErrors(['save' => 'Changes could not be saved. No changes were committed; please retry.'])->withInput($request->safeInput());
        }

        return redirect($request->destination())->with('status', 'Private request handling saved. No notification was sent.');
    }

    public function destroy(InquiryActionRequest $request, InquiryManager $manager)
    {
        try {
            $manager->delete($request->inquiry()->id, $request->inquiry()->kind, $request->validated('expected_version'), $request->user());
        } catch (ValidationException $exception) {
            return redirect($request->destination())->withErrors($exception->errors());
        } catch (\PDOException) {
            return redirect($request->destination())->withErrors(['save' => 'The active record could not be deleted. Please retry.']);
        }

        return redirect()->route($this->definition($request)['route'].'.index')->with('status', 'The active database record and its local status history were deleted. Existing backups were not changed.');
    }
}
