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
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique(['device_id']); // Remove global unique constraint
            $table->unique(['user_id', 'device_id']); // Add scoped unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'device_id']); // Remove scoped unique constraint
            $table->unique('device_id'); // Restore global unique constraint
        });
    }
};
