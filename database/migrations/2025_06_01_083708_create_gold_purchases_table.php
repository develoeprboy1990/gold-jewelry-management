<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gold_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();
            $table->decimal('weight', 8, 3);
            $table->string('karat');
            $table->decimal('pure_weight', 8, 3);
            $table->decimal('karrat_24', 8, 3);
            $table->decimal('rate', 8, 2);
            $table->decimal('amount', 12, 2);
            $table->string('type'); // pure_gold, used_gold
            $table->text('description')->nullable();
            $table->date('date');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->string('customer_name')->nullable();
            $table->string('contact_no')->nullable();
            $table->text('address')->nullable();
            $table->decimal('cash_payment', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gold_purchases');
    }
};
