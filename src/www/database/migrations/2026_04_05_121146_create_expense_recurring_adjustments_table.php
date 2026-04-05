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
        Schema::create('expense_recurring_adjustments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->ulid('payment_method_id')->nullable();
            $table->ulid('category_id')->nullable();

            $table->integer('amount');

            $table->integer('interval_months');
            $table->date('start_month');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_recurring_adjustments');
    }
};
