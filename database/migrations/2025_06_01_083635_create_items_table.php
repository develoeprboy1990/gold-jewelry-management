<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number')->unique();
            $table->string('bar_code')->nullable();
            $table->foreignId('category_id')->constrained('item_categories');
            $table->string('group_item');
            $table->string('sub_group_item')->nullable();
            $table->string('sub_item')->nullable();
            $table->decimal('weight', 8, 3);
            $table->integer('quantity')->default(1);
            $table->integer('pieces')->default(1);
            $table->string('karat')->nullable();
            $table->decimal('pure_weight', 8, 3)->nullable();
            $table->string('design_no')->nullable();
            $table->string('worker_name')->nullable();
            $table->string('worker_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('making_cost', 10, 2)->default(0);
            $table->decimal('stone_price', 10, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->json('images')->nullable();
            $table->enum('status', ['in_stock', 'sold', 'on_order'])->default('in_stock');
            $table->date('date_created')->default(now());
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
