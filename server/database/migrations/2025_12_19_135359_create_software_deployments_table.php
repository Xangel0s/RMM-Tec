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
        Schema::create('software_deployments', function (Blueprint $table) {
            $table->id();
            $table->string('software_name');
            $table->string('installer_url');
            $table->json('endpoints'); // json
            $table->string('status')->default('pending'); // pending, in_progress, completed, failed
            $table->timestamp('deployment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('software_deployments');
    }
};
