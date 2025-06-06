<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = \App\Constants\TicketStatusConstant::getStatuses();

        foreach ($statuses as $status) {
            \App\Models\TicketStatus::updateOrCreate(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
