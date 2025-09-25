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
    Schema::create('ap_user_roles', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->text('description')->nullable();
      $table->foreignId('role_id')->nullable()->references('id')->on('ap_roles');
      $table->foreignId('user_id')->nullable();
      $table->tinyInteger('category')->nullable(); //1 = AAS, 2 = Pegadaian
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_user_roles');
  }
};
