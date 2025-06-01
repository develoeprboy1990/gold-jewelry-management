<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders');
            $table->string('item_type'); // ring, bangle, necklace, etc.
            $table->string('description');
            $table->integer('quantity');
            $table->decimal('estimated_weight', 8, 3)->nullable();
            $table->string('karat')->nullable();
            $table->decimal('estimated_making_cost', 10, 2)->nullable();
            $table->decimal('estimated_stone_cost', 10, 2)->nullable();
            $table->decimal('estimated_total', 10, 2);
            $table->json('specifications')->nullable(); // size, design details, etc.
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->foreignId('final_item_id')->nullable()->constrained('items'); // links to actual item when created
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
