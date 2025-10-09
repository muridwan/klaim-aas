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
        Schema::create('ap_subrogation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('subrogation_id')->constrained('ap_subrogations')->onDelete('cascade');
            $table->string('document_name');
            $table->string('file_path');
            $table->string('document_type')->nullable(); // surat, bukti_transfer, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrogation_documents');
    }
};
