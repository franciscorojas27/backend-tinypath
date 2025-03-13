<?php

namespace Database\Seeders;

use App\Models\Metric;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Metric::factory(100)->create();
    }
}
