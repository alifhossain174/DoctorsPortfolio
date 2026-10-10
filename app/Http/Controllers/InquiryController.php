<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Services\InquirySubmission;
use App\Services\InquirySubmissionTokens;
use App\Services\WebsiteContent;
use Illuminate\Validation\ValidationException;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request, WebsiteContent $site, InquirySubmission $submission, InquirySubmissionTokens $tokens)
    {
        $data = $request->safe()->only(['kind', 'name', 'phone', 'message', 'consultation_option_id']);
        $data['locale'] = app()->getLocale();
        $data['message'] ??= null;
        if (isset($data['consultation_option_id'])) {
            $data['consultation_option_id'] = (int) $data['consultation_option_id'];
        }
        try {
            $token = $request->validated('submission_token');
            $inquiry = $submission->save($data, $token, $tokens->wasSaved($request, $token));
        } catch (ValidationException $exception) {
            return redirect(StoreInquiryRequest::destination($request))->withErrors($exception->errors())->withInput(StoreInquiryRequest::safeOldInput($request));
        } catch (\PDOException $exception) {
            // Never log SQL bindings, request bodies or patient details.
            return StoreInquiryRequest::feedback($request, 'contact.save_failure');
        }

        // The transaction has completed before any success is flashed.
        $tokens->markSaved($request, $token);

        return redirect(StoreInquiryRequest::destination($request))->with('inquiry_success', $site->text('contact.success_'.$data['kind']))->with('inquiry_reference', $inquiry->public_reference);
    }
}
