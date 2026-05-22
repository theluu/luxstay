<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen VARCHAR columns to TEXT so they can store JSON translations.
        // spatie/laravel-translatable stores JSON in the field's own column,
        // e.g. name becomes {"vi":"Phòng Deluxe","en":"Deluxe Room","zh":"豪华客房"}
        Schema::table('rooms', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('title')->change();
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->text('title')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->text('title')->nullable()->change();
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('rooms',              fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('posts',              fn (Blueprint $t) => $t->string('title')->change());
        Schema::table('activities',         fn (Blueprint $t) => $t->string('title')->change());
        Schema::table('products',           fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('sliders',            fn (Blueprint $t) => $t->string('title')->nullable()->change());
        Schema::table('post_categories',    fn (Blueprint $t) => $t->string('name')->change());
        Schema::table('product_categories', fn (Blueprint $t) => $t->string('name')->change());
    }
};
