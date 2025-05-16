<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('data/branches.json'));
        $branches = json_decode($json, true);
        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['code' => $branch['code']],
                [
                    'code' => $branch['code'],
                    'name' => $branch['name'],
                    'ip_range' => $branch['ip_range'],
                    'active' => $branch['active'],
                ]
            );
        }
    }
}
