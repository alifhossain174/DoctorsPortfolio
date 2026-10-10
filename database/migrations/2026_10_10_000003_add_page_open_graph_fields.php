<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->json('og_title')->nullable();
            $table->json('og_description')->nullable();
            $table->foreignId('og_media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('og_media_asset_id');
            $table->dropColumn(['og_title', 'og_description']);
        });
    }
};
