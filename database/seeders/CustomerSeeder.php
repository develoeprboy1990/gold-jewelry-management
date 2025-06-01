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
        $faker = Faker::create();

        // Create sample customers
        for ($i = 0; $i < 20; $i++) {
            Customer::create([
                'name' => $faker->name,
                'cnic' => $faker->numerify('#####-#######-#'),
                'contact_no' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'birth_date' => $faker->date(),
                'anniversary_date' => $faker->optional()->date(),
                'company' => $faker->optional()->company,
                'house_no' => $faker->buildingNumber,
                'street_no' => $faker->randomNumber(2),
                'block_no' => $faker->randomLetter . '-' . $faker->randomNumber(1),
                'colony' => $faker->streetName,
                'city' => $faker->randomElement(['Peshawar', 'Islamabad', 'Karachi', 'Lahore', 'Quetta']),
                'country' => 'Pakistan',
                'address' => $faker->address,
                'cash_balance' => $faker->randomFloat(2, 0, 50000),
                'payment_preference' => $faker->randomElement(['cash', 'credit_card', 'check', 'pure_gold', 'used_gold']),
            ]);
        }
    }
}   