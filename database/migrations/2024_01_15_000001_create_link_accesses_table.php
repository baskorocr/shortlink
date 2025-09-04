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
        Schema::create('link_accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();
            
            $table->index('link_id');
            $table->index('ip_address');
            $table->index('accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_accesses');
    }
};