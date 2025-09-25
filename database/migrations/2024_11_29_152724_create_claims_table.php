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
    Schema::create('ap_claims', function (Blueprint $table) {
      $table->id();
      $table->string('uuid', 64)->nullable();
      $table->string('code', 64)->nullable();           // code pengajuan
      $table->string('policy', 64)->nullable();         // policy number
      $table->string('certificate', 64)->nullable();    // certificate number
      $table->string('name')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->date('incident_date')->nullable();
      $table->double('tsi_amount')->nullable();
      $table->double('claim_amount')->nullable();
      $table->text('description')->nullable();
      $table->text('response')->nullable();
      $table->tinyInteger('status')->nullable();
      $table->tinyInteger('decision')->nullable();
      $table->integer('order')->nullable();
      $table->integer('sequence')->nullable();
      $table->foreignId('office_id')->nullable()->references('id')->on('hr_offices');           // Cabang
      $table->foreignId('outlet_id')->nullable()->references('id')->on('ap_outlets');           // Outlet
      $table->foreignId('cause_id')->nullable()->references('id')->on('ap_causes');             // COB
      $table->foreignId('occupation_id')->nullable()->references('id')->on('ap_occupations');   // Pekerjaan
      $table->foreignId('position_id')->nullable()->references('id')->on('hr_positions');       // Decision Maker
      $table->foreignId('created_by')->nullable()->references('id')->on('ap_users');            // Outlet
      $table->datetime('submitted_at')->nullable();
      $table->datetime('reviewed_at')->nullable();
      $table->foreignId('reviewed_by')->nullable()->references('id')->on('hr_users');           // Reviewer
      $table->datetime('approved_at')->nullable();
      $table->foreignId('approved_by')->nullable()->references('id')->on('hr_users');           // Decision Maker
      $table->datetime('paid_at')->nullable();
      $table->foreignId('paid_by')->nullable()->references('id')->on('hr_users');               // Payer / Div Keu
      $table->datetime('settled_at')->nullable();
      $table->foreignId('settled_by')->nullable()->references('id')->on('hr_users');            // Closer
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ap_claims');
  }
};
