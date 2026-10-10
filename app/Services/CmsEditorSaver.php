<?php

namespace App\Services;

use App\Models\CmsModel;
use Illuminate\Validation\ValidationException;

final class CmsEditorSaver
{
    public function update(CmsModel $record, array $input, string $prefix): void
    {
        try {
            app(AdminContentWriter::class)->update($record, $input);
        } catch (ValidationException $error) {
            $errors = [];
            foreach ($error->errors() as $field => $messages) {
                $errors[$prefix.'.'.$field] = $messages;
            }
            throw ValidationException::withMessages($errors);
        }
    }
}
