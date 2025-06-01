<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users'); // who took the order
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->date('promised_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->enum('order_type', ['custom_order', 'repair', 'size_adjustment'])->default('custom_order');
            $table->decimal('estimated_total', 12, 2);
            $table->decimal('advance_payment', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2)->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('customer_requirements')->nullable(); // sizes, preferences, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};