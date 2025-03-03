<?php

namespace Database\Seeders;

use App\Models\CentralServicesAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateCentralServicesAdminsSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'user_id' => '9d6892d8-1a8f-42fe-b1c5-568e011314d0',
                'first_name' => 'Central Services Admin',
                'last_name' => 'JKUAT',
                'phone_number' => '+254700611118',
                'staff_id' => 'jkuat0900/cs',
            ],
        ];

        foreach ($admins as $key => $admin) {
            CentralServicesAdmin::create($admin);
        }
    }
}