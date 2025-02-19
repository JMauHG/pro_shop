<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Pending', 'type' => 'cart'],
            ['name' => 'Completed', 'type' => 'cart'],
            ['name' => 'Cancelled', 'type' => 'cart'],
            ['name' => 'Processing', 'type' => 'order'],
            ['name' => 'Shipped', 'type' => 'order'],
            ['name' => 'Delivered', 'type' => 'order'],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(
                ['name' => $status['name'], 'type' => $status['type']],
                ['status' => $status['status']]
            );
        }
    }
}
