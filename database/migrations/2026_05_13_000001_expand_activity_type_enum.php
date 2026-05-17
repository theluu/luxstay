<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE activities MODIFY type ENUM('spa','golf','hiking','skiing','water_sports','fitness','nature','restaurant','event','leisure','unique') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE activities MODIFY type ENUM('spa','golf','hiking','skiing','water_sports','fitness','nature','restaurant','event') NOT NULL");
    }
};
