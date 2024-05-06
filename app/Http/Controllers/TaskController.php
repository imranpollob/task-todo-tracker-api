<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\delete;

class TaskController extends BaseController
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        $tasks = $tasks->map(function ($task) {
            $elapsed_time = $task->taskTimes()->whereDate('created_at', Carbon::today())->sum('elapsed_time');
            $task->elapsed_time = $elapsed_time;
            return $task;
        });

        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task->taskTimes()->create([
            'elapsed_time' => $input['elapsed_time'],
        ]);

        return $this->sendResponse($task, 'Time added to task successfully.');
    }
}
