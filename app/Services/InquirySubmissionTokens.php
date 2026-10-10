<?php

namespace App\Services;

use Illuminate\Http\Request;

final class InquirySubmissionTokens
{
    public function issue(Request $request): string
    {
        $tokens = array_filter($request->session()->get('inquiry_submission_tokens', []), fn ($issued) => is_int($issued) && $issued > time() - 7200);
        $tokens = array_slice($tokens, -19, null, true);
        $token = bin2hex(random_bytes(32));
        $tokens[hash('sha256', $token)] = time();
        $request->session()->put('inquiry_submission_tokens', $tokens);
        $request->session()->put('inquiry_submission_receipts', array_intersect_key($request->session()->get('inquiry_submission_receipts', []), $tokens));

        return $token;
    }

    public function valid(Request $request, mixed $token): bool
    {
        if (! is_string($token) || ! preg_match('/^[a-f0-9]{64}$/D', $token)) {
            return false;
        }
        $issued = $request->session()->get('inquiry_submission_tokens.'.hash('sha256', $token));

        // Used tokens can replay only the identical persisted request, never insert again.
        return is_int($issued) && $issued > time() - 7200;
    }

    public function wasSaved(Request $request, string $token): bool
    {
        return $request->session()->get('inquiry_submission_receipts.'.hash('sha256', $token)) === true;
    }

    public function markSaved(Request $request, string $token): void
    {
        $request->session()->put('inquiry_submission_receipts.'.hash('sha256', $token), true);
    }
}
