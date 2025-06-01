<?php
// database/seeders/ItemCategorySeeder.php
namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Rings', 'type' => 'gold'],
            ['name' => 'Necklaces', 'type' => 'gold'],
            ['name' => 'Earrings', 'type' => 'gold'],
            ['name' => 'Bracelets', 'type' => 'gold'],
            ['name' => 'Bangles', 'type' => 'gold'],
            ['name' => 'Chains', 'type' => 'gold'],
            ['name' => 'Pendants', 'type' => 'gold'],
            ['name' => 'Diamond Rings', 'type' => 'diamond'],
            ['name' => 'Diamond Necklaces', 'type' => 'diamond'],
            ['name' => 'Diamond Earrings', 'type' => 'diamond'],
            ['name' => 'Silver Rings', 'type' => 'silver'],
            ['name' => 'Silver Chains', 'type' => 'silver'],
            ['name' => 'Platinum Rings', 'type' => 'platinum'],
            ['name' => 'Wedding Sets', 'type' => 'gold'],
            ['name' => 'Traditional Sets', 'type' => 'gold'],
        ];

        foreach ($categories as $category) {
            ItemCategory::create($category);
        }
    }
}