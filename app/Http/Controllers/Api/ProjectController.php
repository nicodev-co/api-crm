<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        try {
            $project = Project::create($request->validated());

            return response()->json(new ProjectResource($project), Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'title' => 'Validation Error',
                    'detail' => $e->errors(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'title' => 'Server Error',
                    'detail' => 'An unexpected error occurred. Please try again later.',
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $project = Project::findOrFail($id);

            return response()->json(new ProjectResource($project), Response::HTTP_ACCEPTED);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_NOT_FOUND,
                    'title' => 'Resource not found',
                    'detail' => 'The requested project does not exist',
                ],
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->update($request->validated());

            return response()->json(['data' => new ProjectResource($project)], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_NOT_FOUND,
                    'title' => 'Resource not found',
                    'detail' => 'The requested project does not exist',
                ],
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'title' => 'Validation Error',
                    'detail' => $e->errors(),
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'title' => 'Server Error',
                    'detail' => 'An unexpected error occurred. Please try again later.',
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_NOT_FOUND,
                    'title' => 'Resource not found',
                    'detail' => 'The requested project does not exist',
                ],
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'title' => 'Server Error',
                    'detail' => 'An unexpected error occurred. Please try again later.',
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
