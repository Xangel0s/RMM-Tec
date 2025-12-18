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
        Schema::create('metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('endpoint_id')->constrained('endpoints')->cascadeOnDelete();
            $table->double('cpu_usage')->nullable();
            $table->double('ram_usage')->nullable(); // In GB or Percentage? Agent sends both. Let's store percentage for now, or maybe both? Let's start with percentage.
            $table->double('ram_total')->nullable(); // Store total to calculate if needed
            $table->double('disk_usage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};
