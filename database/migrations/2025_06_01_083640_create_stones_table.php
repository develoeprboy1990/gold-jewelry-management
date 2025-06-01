<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->string('type');
            $table->string('name');
            $table->decimal('weight', 8, 3);
            $table->integer('quantity');
            $table->decimal('rate', 10, 2);
            $table->decimal('price', 10, 2);
            $table->string('color')->nullable();
            $table->string('cut')->nullable();
            $table->string('clarity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stones');
    }
};
