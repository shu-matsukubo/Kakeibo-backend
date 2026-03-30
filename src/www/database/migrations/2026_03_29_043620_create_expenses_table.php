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
        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->integer('amount');
            $table->ulid('payment_method_id');
            $table->ulid('category_id');
            $table->string('memo')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
