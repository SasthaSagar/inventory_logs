<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;


class inventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inventory::factory()
            ->count(50)
            ->create();
    }
}
