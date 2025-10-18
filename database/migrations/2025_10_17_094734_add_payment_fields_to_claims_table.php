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
        Schema::table('ap_claims', function (Blueprint $table) {
            //
            $table->string('payment_number')->nullable();
            $table->string('payment_receiver')->nullable();
            $table->text('payment_description')->nullable();
            $table->string('payment_document')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ap_claims', function (Blueprint $table) {
            //
        });
    }
};
