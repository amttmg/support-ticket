<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use App\Models\SupportUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ITDepartmentSetup extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'company' => [
                'name' => 'Rastriya Banijya Bank Ltd',
            ],
            'departments' => [
                [
                    'name' => 'IT Department',
                    'support_units' => [
                        ['name' => 'CBS Support Unit'],
                        ['name' => 'Network Support Unit'],
                        ['name' => 'Data Center Unit'],
                        ['name' => 'Internal Administration Unit'],
                        ['name' => 'Development and Implementation Unit']
                    ],
                ],
                [
                    'name' => 'Digital Banking Department',
                    'support_units' => []
                ],
            ],
        ];



        $company = Company::updateOrCreate(
            ['name' => $data['company']['name']],
            $data['company']
        );

        foreach ($data['departments'] as $deptData) {
            $department = Department::updateOrCreate(
                ['name' => $deptData['name']],
                [
                    'name' => $deptData['name'],
                    'company_id' => $company->id,
                ]
            );

            foreach ($deptData['support_units'] as $unitData) {
                SupportUnit::updateOrCreate(
                    ['name' => $unitData['name']],
                    [
                        'name' => $unitData['name'],
                        'department_id' => $department->id,
                    ]
                );
            }
        }
    }
}
