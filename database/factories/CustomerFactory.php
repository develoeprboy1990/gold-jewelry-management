<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Enums\PaymentPreference;
//use App\Models\CustomerGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // or pick a random user
          //  'customer_group_id' => CustomerGroup::factory(), // or null
            'name' => $this->faker->name,
            'cnic' => $this->faker->numerify('#####-#######-#'),
            'contact_no' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'birth_date' => $this->faker->date(),
            'anniversary_date' => $this->faker->date(),
            'company' => $this->faker->company,
            'house_no' => $this->faker->buildingNumber,
            'street_no' => $this->faker->streetName,
            'block_no' => $this->faker->randomLetter,
            'colony' => $this->faker->word,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'address' => $this->faker->address,
            'cash_balance' => $this->faker->randomFloat(2, 0, 10000),
            'payment_preference' => $this->faker->randomElement(PaymentPreference::class),
            'points' => $this->faker->numberBetween(0, 1000),
            'is_active' => $this->faker->boolean,
        ];
    }
}
