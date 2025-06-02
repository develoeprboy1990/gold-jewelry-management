<?php
// database/migrations/create_customers_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentPreference;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_group_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('cnic')->nullable();
            $table->string('contact_no');
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('anniversary_date')->nullable();
            $table->string('company')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street_no')->nullable();
            $table->string('block_no')->nullable();
            $table->string('colony')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->decimal('cash_balance', 10, 2)->default(0.00);
            $table->enum('payment_preference', array_column(PaymentPreference::cases(), 'value'))->default(PaymentPreference::CASH);
            $table->integer('points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
