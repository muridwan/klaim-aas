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
    Schema::create('ap_recommendations', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->string('code', 64)->nullable();
      $table->tinyInteger('sequence')->nullable();
      $table->tinyInteger('suggestion')->nullable();
      $table->text('description')->nullable();
      $table->text('attachment')->nullable();
      $table->boolean('is_decider')->nullable();
      $table->foreignId('claim_id')->nullable()->references('id')->on('ap_claims');
      $table->foreignId('position_id')->nullable()->references('id')->on('hr_positions');
      $table->foreignId('created_by')->nullable()->references('id')->on('hr_users');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_recommendations');
  }
};
