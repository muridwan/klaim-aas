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
        Schema::create('ap_subrogation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('subrogation_id')->constrained('ap_subrogations')->onDelete('cascade');
            $table->string('status_before')->nullable();
            $table->string('status_after');
            $table->text('remarks')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('ap_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrogation_histories');
    }
};
