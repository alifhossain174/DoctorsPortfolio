<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->char('submission_token_hash', 64)->nullable()->unique();
            $table->char('submission_fingerprint', 64)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropUnique(['submission_token_hash']);
            $table->dropColumn(['submission_token_hash', 'submission_fingerprint']);
        });
    }
};
