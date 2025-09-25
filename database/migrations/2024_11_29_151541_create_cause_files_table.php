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
		Schema::create('ap_cause_files', function (Blueprint $table) {
			$table->id();
			$table->string('uuid', 64)->nullable();
			$table->text('description')->nullable();
			$table->foreignId('cause_id')->nullable()->references('id')->on('ap_causes');
			$table->foreignId('file_id')->nullable()->references('id')->on('ap_files');
			$table->dateTime('set_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ap_cause_files');
	}
};
