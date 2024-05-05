<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskTime;
use Illuminate\Support\Carbon;

class TaskTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();

        foreach ($tasks as $task) {
            TaskTime::create([
                'task_id' => $task->id,
                'elapsed_time' => 30,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
            ]);

            TaskTime::create([
                'task_id' => $task->id,
                'elapsed_time' => 30,
                'created_at' => Carbon::yesterday(),
                'updated_at' => Carbon::yesterday(),
            ]);
        }
    }
}
