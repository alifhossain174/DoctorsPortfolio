<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([InitialWebsiteContentSeeder::class, InquiryFormContentSeeder::class, PublicInterfaceContentSeeder::class]);
        // No default users, passwords or fake inquiries.
    }
}
