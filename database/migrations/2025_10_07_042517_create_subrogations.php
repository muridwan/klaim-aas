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
        Schema::create('ap_subrogations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('claim_id')->constrained('ap_claims')->onDelete('cascade');
            $table->string('third_party_name');
            $table->string('third_party_type')->nullable(); // misal: 'leasing', 'pegadaian', 'pihak_3'
            $table->decimal('subrogation_amount', 18, 2);
            $table->decimal('recovered_amount', 18, 2)->default(0);
            $table->date('submission_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('draft'); // draft, submitted, paid, closed
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrogations');
    }
};
