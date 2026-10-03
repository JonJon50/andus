<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('services')->insert([
            [
                'title' => 'Business Website Development',
                'slug' => 'business-website-development',
                'description' => 'Professional websites built to clearly present your services, build trust with customers, and support future business growth.',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Custom Web Applications',
                'slug' => 'custom-web-applications',
                'description' => 'Internal tools, dashboards, portals, and database-driven systems designed around the way your business actually works.',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Workflow Automation',
                'slug' => 'workflow-automation',
                'description' => 'Automation solutions that reduce repetitive work, improve response time, and help your team stay organized.',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Database & Reporting Solutions',
                'slug' => 'database-reporting-solutions',
                'description' => 'Better ways to store, manage, clean, and report on business data so you can make smarter decisions.',
                'icon' => null,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('services')
            ->whereIn('slug', [
                'business-website-development',
                'custom-web-applications',
                'workflow-automation',
                'database-reporting-solutions',
            ])
            ->delete();
    }
};
