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
            ['name' => 'pending', 'type' => 'cart'],
            ['name' => 'completed', 'type' => 'cart'],
            ['name' => 'cancelled', 'type' => 'cart'],
            ['name' => 'created', 'type' => 'order'],
            ['name' => 'shipped', 'type' => 'order'],
            ['name' => 'delivered', 'type' => 'order'],
        ];

        foreach ($statuses as $status) {
            Status::firstOrCreate(
                ['name' => $status['name'], 'type' => $status['type']],
            );
        }
    }
}
