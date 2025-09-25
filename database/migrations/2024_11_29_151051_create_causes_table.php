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
        Schema::create('ap_causes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->nullable();
            $table->string('code', 32)->nullable();
            $table->string('name', 128)->nullable();
            $table->text('description')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->datetime('inactive_date')->nullable();
            $table->foreignId('business_id')->nullable()->references('id')->on('ap_businesses');
            $table->foreignId('institution_id')->nullable()->references('id')->on('ap_institutions');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ap_causes');
    }
};
