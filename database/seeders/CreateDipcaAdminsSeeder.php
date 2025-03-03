<?php

namespace Database\Seeders;

use App\Models\DipcaAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDipcaAdminsSeeder extends Seeder
{
    /** 
     * Run the database seeds.
     */
    public function run(): void
    {
        $dipca_admins = [
            [
                'user_id' => '9d6892d8-38b4-41af-a038-a000c8a6f880',
                'first_name' => 'DIPCA ADMIN',
                'last_name' => 'JKUAT',
                'staff_id' => 'dipca001-224',
                'phone_number' => '+254722011144',
            ],
        ];

        foreach ($dipca_admins as $key => $dipca_admin) {
            DipcaAdmin::create($dipca_admin);
        }
    }
}