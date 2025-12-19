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
        Schema::create('alert_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('endpoint_id')->nullable()->constrained('endpoints')->cascadeOnDelete();
            $table->string('metric'); // cpu, ram, disk, status
            $table->integer('threshold')->default(80); // percentage or minutes (for status)
            $table->integer('duration_seconds')->default(300); // Trigger only if sustained for X seconds
            $table->string('action')->default('email'); // email, webhook
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('alert_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_rule_id')->constrained('alert_rules')->cascadeOnDelete();
            $table->foreignUuid('endpoint_id')->constrained('endpoints')->cascadeOnDelete();
            $table->timestamp('triggered_at');
            $table->timestamp('resolved_at')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_incidents');
        Schema::dropIfExists('alert_rules');
    }
};
