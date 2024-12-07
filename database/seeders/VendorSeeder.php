<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::truncate();

        Vendor::create([
            'name' => 'Vendor User',
            'email' => 'vendor@gmail.com',
            'password' => 'password@123',
        ]);
    }
}
