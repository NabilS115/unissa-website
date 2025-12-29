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
        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id'); // Who performed the action
            $table->unsignedBigInteger('target_user_id')->nullable(); // User being acted upon
            $table->string('action'); // create, update, delete, activate, deactivate, restore
            $table->string('entity_type')->default('user'); // user, order, product, etc.
            $table->json('old_values')->nullable(); // Previous values for updates
            $table->json('new_values')->nullable(); // New values for updates
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['admin_id', 'created_at']);
            $table->index(['target_user_id', 'created_at']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_audit_logs');
    }
};
