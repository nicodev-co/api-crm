<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_manager_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => 'required|string|max:10',
            'name_tasks' => 'string|max:50',
        ];
    }
}
