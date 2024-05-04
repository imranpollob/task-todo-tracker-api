<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            ['name' => 'Research', 'elapsed_time' => 0],
            ['name' => 'Husle', 'elapsed_time' => 0],
            ['name' => 'Gaming', 'elapsed_time' => 0],
            ['name' => 'Sleep', 'elapsed_time' => 0],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
