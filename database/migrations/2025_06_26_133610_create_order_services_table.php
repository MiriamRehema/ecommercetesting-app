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
        Schema::create('order_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_requests_id')->constrained('service_requests')->cascadeOnDelete();
            $table->foreignId('services_id')->constrained('services')->cascadeOnDelete();
            $table->Integer('quantity')->default(1);
            $table->decimal('unit_amount',)->nullable();
            $table->decimal('total_amount')->constrained('service_requests')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_services');
    }
};
