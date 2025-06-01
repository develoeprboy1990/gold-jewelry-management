<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('weight', 8, 3);
            $table->decimal('waste_percentage', 5, 2)->default(0);
            $table->decimal('waste_weight', 8, 3)->default(0);
            $table->decimal('total_weight', 8, 3);
            $table->decimal('making_per_gram', 8, 2);
            $table->decimal('total_making', 10, 2);
            $table->decimal('stone_price', 10, 2)->default(0);
            $table->decimal('other_charges', 10, 2)->default(0);
            $table->decimal('gold_rate', 8, 2);
            $table->decimal('gold_price', 12, 2);
            $table->decimal('gross_weight', 8, 3);
            $table->decimal('total_price', 12, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_price', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_items');
    }
};
