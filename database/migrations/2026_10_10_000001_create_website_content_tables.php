<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->index();
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('title');
            $table->json('alt_text')->nullable();
            $table->json('provenance_note')->nullable();
            $table->json('stock_note')->nullable();
            $table->text('source_url')->nullable();
            $table->string('disk')->nullable();
            $table->string('path', 500)->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('original_filename')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('doctor_name');
            $table->json('subtitle');
            $table->json('doctor_biography');
            $table->json('credential');
            $table->json('department');
            $table->json('institution');
            $table->json('clinic_name');
            $table->json('address');
            $table->json('consultation_hours');
            $table->json('friday_note');
            $table->json('location_label');
            $table->json('facebook_url')->nullable();
            $table->json('map_url')->nullable();
            $table->string('default_locale');
            $table->string('brand_icon');
            $table->foreignId('portrait_media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->json('footer_description');
            $table->json('stock_notice');
            $table->json('medical_disclaimer');
            $table->json('cta_title');
            $table->json('cta_description');
            $table->json('cta_label');
            $table->json('cta_target');
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('label');
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('content_fields', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->json('value')->nullable();
            $table->foreignId('media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->foreignId('page_id')->constrained('pages')->restrictOnDelete();
            $table->unsignedInteger('sort_order');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('navigation_items', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('label');
            $table->json('target');
            $table->string('placement');
            $table->unsignedInteger('sort_order');
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('care_cards', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('title');
            $table->json('description');
            $table->json('icon');
            $table->json('link_label');
            $table->json('target');
            $table->unsignedInteger('sort_order');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->string('slug', 150)->unique();
            $table->json('title');
            $table->json('body');
            $table->unsignedInteger('sort_order');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('consultation_options', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('label');
            $table->unsignedInteger('sort_order');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->json('label');
            $table->unsignedInteger('sort_order');
            $table->boolean('for_blog')->default(false);
            $table->boolean('for_gallery')->default(false);
            $table->timestamps();
            $table->index('sort_order');
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->json('title');
            $table->json('excerpt');
            $table->json('body');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('media_asset_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->json('editorial_badge');
            $table->json('image_alt')->nullable();
            $table->unsignedInteger('sort_order');
            $table->boolean('on_homepage')->default(false);
            $table->string('status')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->timestamps();
            $table->index('sort_order');
            $table->index(['status', 'on_homepage', 'sort_order']);
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('media_asset_id')->constrained('media_assets')->restrictOnDelete();
            $table->json('caption');
            $table->json('alt_text')->nullable();
            $table->unsignedInteger('sort_order');
            $table->boolean('is_visible')->default(true);
            $table->boolean('on_homepage')->default(false);
            $table->timestamps();
            $table->index('sort_order');
            $table->index(['is_visible', 'on_homepage', 'sort_order']);
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('public_reference', 26)->unique();
            $table->string('kind', 20)->index();
            $table->string('name', 80);
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->foreignId('consultation_option_id')->nullable()->constrained()->nullOnDelete();
            $table->string('consultation_key', 100)->nullable();
            $table->json('consultation_label')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 2);
            $table->string('status', 20)->default('new')->index();
            $table->text('internal_notes')->nullable();
            $table->timestamp('confirmed_visit_at')->nullable()->index();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
        Schema::create('content_initializations', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->string('snapshot_sha256', 64);
            $table->timestamp('completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_initializations');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('consultation_options');
        Schema::dropIfExists('services');
        Schema::dropIfExists('care_cards');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('content_fields');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('media_assets');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));
    }
};
