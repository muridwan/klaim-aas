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
    Schema::create('ap_users', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->string('code', 32)->nullable();
      $table->string('name')->nullable();
      $table->string('email')->nullable();
      $table->string('phone', 16)->nullable();
      $table->string('username')->nullable();
      $table->string('password')->nullable();
      $table->string('bypass_code')->nullable();
      $table->boolean('get_notification')->nullable();
      $table->string('photo')->nullable();
      $table->datetime('reset_at')->nullable();
      $table->foreignId('outlet_id')->nullable()->references('id')->on('ap_outlets');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_users');
  }
};
