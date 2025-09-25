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
    Schema::create('ap_limits', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->text('description')->nullable();
      $table->double('amount')->nullable();
      $table->foreignId('cause_id')->nullable()->references('id')->on('ap_causes');
      $table->foreignId('office_id')->nullable()->references('id')->on('hr_offices');
      $table->foreignId('position_id')->nullable()->references('id')->on('hr_positions');
      $table->datetime('effective_date')->nullable();
      $table->datetime('inactive_date')->nullable();
      $table->boolean('is_leaf')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_limits');
  }
};
