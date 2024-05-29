<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\delete;

class TaskController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $day = $request->query('day') ?? 0;
        $date = Carbon::today()->addDays((int) $day);
        $tasks = Task::where('user_id', auth()->id())->get();

        $tasks = $tasks->map(function ($task) use ($date) {
            $elapsed_time = $task->taskTimes()->whereDate('date', $date)->sum('elapsed_time');
            $task->elapsed_time = (int) $elapsed_time;

            // Get the latest task time for each task
            // $latest_task_time = $task->taskTimes()->latest('created_at')->first();
            // $task->last_updated_time = $latest_task_time ? $latest_task_time->created_at : null;

            return $task;
        });

        // Get the latest task time for all tasks of the authenticated user
        $task_ids = $tasks->pluck('id');
        $latest_task_time = TaskTime::whereIn('task_id', $task_ids)->latest('created_at')->first();
        $latest_task_time = $latest_task_time ? $latest_task_time->created_at : null;


        return $this->sendResponse($tasks, 'Tasks retrieved successfully.', $latest_task_time);
    }


    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['user_id'] = auth()->id();
        $task = Task::create($input);
        $task['elapsed_time'] = 0;

        return $this->sendResponse($task, 'Task created successfully.');
    }


    public function show($id): JsonResponse
    {
        $task = Task::with('taskTimes')->first();

        if (is_null($task)) {
            return $this->sendError('Task not found.');
        }

        return $this->sendResponse($task, 'Task retrieved successfully.');
    }


    public function update(Request $request, Task $task): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->name = $input['name'];
        $task->save();

        return $this->sendResponse($task, 'Task updated successfully.');
    }


    public function destroy(Task $task): JsonResponse
    {
        $task->taskTimes()->delete();
        $task->delete();

        return $this->sendResponse([], 'Task deleted successfully.');
    }


    public function addTime(Request $request, Task $task): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'elapsed_time' => 'required|integer',
            'day' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->taskTimes()->create([
            'elapsed_time' => $input['elapsed_time'],
            'date' => Carbon::today()->addDays($input['day'] ?? 0),
        ]);

        return $this->sendResponse($task, 'Time added to task successfully.');
    }
}
