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
        Schema::create('ap_logs', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->foreignId('EXT_created_by')->nullable()->references('id')->on('ap_users');
            $table->foreignId('AAS_created_by')->nullable()->references('id')->on('hr_users');
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_logs');
    }
};
