<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_investments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->decimal('amount', 28, 8)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('wallet_address_used')->nullable();
            $table->string('tx_hash')->nullable();
            $table->text('proof_url')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->decimal('expected_return', 28, 8)->default(0);
            $table->dateTime('maturity_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('investment_plans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_investments');
    }
};
