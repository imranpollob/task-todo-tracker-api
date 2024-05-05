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
            ['name' => 'Research'],
            ['name' => 'Husle'],
            ['name' => 'Gaming'],
            ['name' => 'Sleep'],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
