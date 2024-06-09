<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('users')->paginate();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated());

        if ($request->has('users_id')) {
            $task->users()->sync($request->input('users_id'));
        }

        return response()->json(new TaskResource($task), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('users');

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        if ($request->has('users_id')) {
            $task->users()->sync($request->input('users_id'));
        }

        return response()->json(new TaskResource($task), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
