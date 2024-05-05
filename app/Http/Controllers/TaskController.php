<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends BaseController
{
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
    }


    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'elapsed_time' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $task = Task::create($input);

        return $this->sendResponse($task, 'Task created successfully.');
    }


    public function show($id): JsonResponse
    {
        $task = Task::find($id);

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
        $task->delete();

        return $this->sendResponse([], 'Task deleted successfully.');
    }
}
