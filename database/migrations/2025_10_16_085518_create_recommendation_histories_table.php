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
        Schema::create('ap_recommendation_histories', function (Blueprint $table) {
        $table->id();
        $table->uuid('uuid')->unique();
        $table->foreignId('recommendation_id')->constrained('ap_recommendations')->onDelete('cascade');
        $table->foreignId('claim_id')->constrained('ap_claims')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('ap_users')->onDelete('cascade');
        $table->text('note');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_histories');
    }
};
