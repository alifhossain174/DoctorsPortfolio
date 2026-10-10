<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('og_media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
        });
        Schema::create('post_slug_redirects', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->id();
            $table->string('slug', 150)->unique();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_slug_redirects');
        Schema::table('posts', fn (Blueprint $table) => $table->dropConstrainedForeignId('og_media_asset_id'));
    }
};
