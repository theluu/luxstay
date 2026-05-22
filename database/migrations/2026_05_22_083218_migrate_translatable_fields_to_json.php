<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Convert plain-string values to {"vi": "original"} JSON so spatie/laravel-translatable
     * can read them correctly after HasTranslations was added to the models.
     */
    public function up(): void
    {
        $map = [
            'rooms'               => ['name', 'description'],
            'posts'               => ['title', 'excerpt', 'content'],
            'activities'          => ['title', 'content'],
            'products'            => ['name', 'description'],
            'sliders'             => ['title'],
            'post_categories'     => ['name'],
            'product_categories'  => ['name'],
        ];

        foreach ($map as $table => $columns) {
            foreach ($columns as $column) {
                DB::statement("
                    UPDATE `{$table}`
                    SET `{$column}` = JSON_OBJECT('vi', `{$column}`)
                    WHERE `{$column}` IS NOT NULL
                      AND `{$column}` != ''
                      AND JSON_VALID(`{$column}`) = 0
                ");
            }
        }
    }

    public function down(): void
    {
        $map = [
            'rooms'               => ['name', 'description'],
            'posts'               => ['title', 'excerpt', 'content'],
            'activities'          => ['title', 'content'],
            'products'            => ['name', 'description'],
            'sliders'             => ['title'],
            'post_categories'     => ['name'],
            'product_categories'  => ['name'],
        ];

        foreach ($map as $table => $columns) {
            foreach ($columns as $column) {
                DB::statement("
                    UPDATE `{$table}`
                    SET `{$column}` = JSON_UNQUOTE(JSON_EXTRACT(`{$column}`, '$.vi'))
                    WHERE JSON_VALID(`{$column}`) = 1
                      AND JSON_EXTRACT(`{$column}`, '$.vi') IS NOT NULL
                ");
            }
        }
    }
};
