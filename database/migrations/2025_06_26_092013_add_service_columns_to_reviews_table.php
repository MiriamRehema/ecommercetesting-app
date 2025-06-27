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
        Schema::table('reviews', function (Blueprint $table) {
            // $table->foreignId('services_id')->constrained('services')->cascadeOnDelete();
            if (!Schema::hasColumn('reviews', 'service_id')) {
            $table->foreignId('service_id')->constrained()->nullable();}
            $table->decimal('service_rating', 3, 2)->nullable(); // Service rating column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
           
           $table->dropColumn('service_id');
            $table->dropColumn('service_rating');
        });
    }
};
