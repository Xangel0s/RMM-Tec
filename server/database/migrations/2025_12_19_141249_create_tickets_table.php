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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open, in_progress, resolved
            $table->string('priority')->default('medium'); // low, medium, high
            $table->foreignId('user_id')->constrained('users'); // Requester
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Technician
            $table->foreignUuid('endpoint_id')->nullable()->constrained('endpoints')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
