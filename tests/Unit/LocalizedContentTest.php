<?php

namespace Tests\Unit;

use App\Services\LocalizedContent;
use PHPUnit\Framework\TestCase;

class LocalizedContentTest extends TestCase
{
    public function test_selected_locale_and_non_empty_english_fallback(): void
    {
        $service = new LocalizedContent;
        $this->assertSame('বাংলা', $service->resolve(['en' => 'English', 'bn' => 'বাংলা'], 'bn'));
        foreach ([null, '', '  ', [], [null, ' '], ['paragraphs' => []]] as $empty) {
            $this->assertSame(['First paragraph', 'Second paragraph'], $service->resolve(['en' => ['First paragraph', 'Second paragraph'], 'bn' => $empty], 'bn', []));
        }
        $this->assertSame('English', $service->resolve(['en' => 'English', 'bn' => 'বাংলা'], 'fr'));
        $this->assertSame('', $service->resolve(null, 'bn'));
        $this->assertSame([], $service->resolve(['en' => [], 'bn' => null], 'bn', []));
    }

    public function test_structured_content_retains_structure_and_removes_empty_values(): void
    {
        $this->assertSame(['paragraphs' => ['বাংলা']], (new LocalizedContent)->resolve(['en' => [], 'bn' => ['paragraphs' => [null, '', 'বাংলা']]], 'bn', []));
    }
}
