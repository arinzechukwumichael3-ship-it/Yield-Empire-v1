<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('earnings_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('investment_id');
            $table->decimal('amount', 28, 8)->default(0);
            $table->enum('type', ['credited', 'pending'])->default('pending');
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('investment_id')->references('id')->on('user_investments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('earnings_logs');
    }
};
