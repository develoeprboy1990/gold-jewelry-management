<?php
// database/migrations/create_customers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cnic')->unique();
            $table->string('contact_no');
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('anniversary_date')->nullable();
            $table->string('company')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street_no')->nullable();
            $table->string('block_no')->nullable();
            $table->string('colony')->nullable();
            $table->string('city');
            $table->string('country')->default('Pakistan');
            $table->text('address')->nullable();
            $table->decimal('cash_balance', 15, 2)->default(0);
            $table->enum('payment_preference', ['cash', 'credit_card', 'check', 'pure_gold', 'used_gold'])->default('cash');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
