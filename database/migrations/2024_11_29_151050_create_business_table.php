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
        Schema::create('ap_businesses', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->nullable();
            $table->string('code', 32)->nullable();
            $table->string('name', 128)->nullable();
            $table->text('description')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->datetime('inactive_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_businesses');
    }
};
