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
    Schema::create('ap_outlets', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->string('code', 32)->nullable();
      $table->string('name', 128)->nullable();
      $table->text('description')->nullable();
      $table->tinyInteger('level')->nullable();
      $table->foreignId('institution_id')->nullable()->references('id')->on('ap_institutions');
      $table->foreignId('parent_id')->nullable()->references('id')->on('ap_outlets');
      $table->foreignId('office_id')->nullable()->references('id')->on('hr_offices');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_outlets');
  }
};
