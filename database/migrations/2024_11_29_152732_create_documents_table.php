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
    Schema::create('ap_documents', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->string('code', 32)->nullable();
      $table->text('document')->nullable();
      $table->text('description')->nullable();
      $table->boolean('is_accepted')->nullable();
      $table->text('remarks')->nullable();
      $table->foreignId('claim_id')->nullable()->references('id')->on('ap_claims');
      $table->foreignId('cause_file_id')->nullable()->references('id')->on('ap_cause_files');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_documents');
  }
};
