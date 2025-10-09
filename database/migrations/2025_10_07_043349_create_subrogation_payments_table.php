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
        Schema::create('ap_subrogation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('subrogation_id')->constrained('ap_subrogations')->onDelete('cascade');
            $table->decimal('payment_amount', 18, 2);
            $table->date('payment_date');
            $table->string('payment_method')->nullable(); // transfer, cash, dll
            $table->string('reference_number')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subrogation_payments');
    }
};
