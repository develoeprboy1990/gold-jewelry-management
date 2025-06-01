<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_no')->unique();
            $table->string('bill_book_no')->nullable();
            $table->date('sale_date');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users'); // sale_man
            $table->decimal('total_making', 10, 2)->default(0);
            $table->decimal('total_stone_charges', 10, 2)->default(0);
            $table->decimal('total_other_charges', 10, 2)->default(0);
            $table->decimal('total_gold_price', 12, 2)->default(0);
            $table->decimal('total_item_discount', 10, 2)->default(0);
            $table->decimal('bill_discount', 10, 2)->default(0);
            $table->decimal('net_bill', 12, 2);
            $table->decimal('cash_received', 12, 2)->default(0);
            $table->decimal('credit_card_amount', 10, 2)->default(0);
            $table->decimal('check_amount', 10, 2)->default(0);
            $table->decimal('used_gold_amount', 10, 2)->default(0);
            $table->decimal('pure_gold_amount', 10, 2)->default(0);
            $table->decimal('total_received', 12, 2);
            $table->decimal('cash_balance', 12, 2)->default(0);
            $table->date('promise_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
