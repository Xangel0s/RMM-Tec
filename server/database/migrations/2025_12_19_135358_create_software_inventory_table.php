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
        Schema::create('software_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('endpoint_id')->constrained('endpoints')->cascadeOnDelete();
            $table->string('software_name');
            $table->string('version')->nullable();
            $table->timestamp('install_date')->nullable();
            $table->string('publisher')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_inventory');
    }
};
