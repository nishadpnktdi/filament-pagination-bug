<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Staff;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(5)
            ->recycle([Staff::factory()->create(), Store::factory()->create()])
            ->create();
    }
}
