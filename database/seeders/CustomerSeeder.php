<?php
// database/seeders/CustomerSeeder.php
namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::factory()->count(50)->create(); // Adjust count as needed
    }
}
