<?php

namespace Database\Seeders;

use App\Models\DepartmentAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDepartmentAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department_admins = [
            [
                'user_id' => '9d6892d7-5b4c-4c56-a115-d8e031cd1464',
                'department_id' => '9d6893fb-8286-4dee-a7a4-93c3cd41f4ba',
                'first_name' => 'ICT Department',
                'last_name' => 'JKUAT',
                'phone_number' => '+254700011111',
                'staff_id' => 'jkuat0900/ict',
            ],
            [
                'user_id' => '9d6892d7-a561-4935-b97d-673bffc49f76',
                'department_id' => '9d6893fb-f710-49dd-a6b1-21a612ce9b72',
                'first_name' => 'Finance Department',
                'last_name' => 'JKUAT',
                'phone_number' => '+254703311112',
                'staff_id' => 'jkuat0900/fin',
            ],
            [
                'user_id' => '9d6892d7-d0ad-4663-92eb-b3f81da54210',
                'department_id' => '9d6893fb-adb6-4c50-b260-58193d3afb4e',
                'first_name' => 'Transportation',
                'last_name' => 'JKUAT',
                'phone_number' => '+254722011144',
                'staff_id' => 'jkuat0900/trans',
            ],
            [
                'user_id' => '9d6892d7-f38d-4c83-9d25-83c72d95ce2b',
                'department_id' => '9d6893fb-d47b-4538-9f97-ec05604ca5cc',
                'first_name' => 'Human Resource',
                'last_name' => 'JKUAT',
                'phone_number' => '+254700611118',
                'staff_id' => 'jkuat0900/hr',
            ],
        ];

        foreach ($department_admins as $key => $department_admin) {
            DepartmentAdmin::create($department_admin);
        }
    }
}