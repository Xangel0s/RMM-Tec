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
        Schema::create('rustdesk_settings', function (Blueprint $table) {
            $table->id();
            $table->string('server_url'); // "rustdesk.tudominio.com"
            $table->string('relay_server')->nullable();
            $table->string('api_key')->nullable(); // Credenciales RustDesk API
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rustdesk_settings');
    }
};
