<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')
                  ->constrained('post_comments')->nullOnDelete();
            $table->boolean('is_admin_reply')->default(false)->after('body');
            $table->boolean('is_approved')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'is_admin_reply']);
            $table->boolean('is_approved')->default(true)->change();
        });
    }
};
