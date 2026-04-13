<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On modifie l'enum en string pour plus de fléxibilité
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE enrollments MODIFY status VARCHAR(255) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE enrollments MODIFY status ENUM('paid', 'refunded') DEFAULT 'paid'");
    }
};
