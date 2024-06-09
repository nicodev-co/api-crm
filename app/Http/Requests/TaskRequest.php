<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => 'required|boolean',
            'users_id' => 'array|exists:users,id',
        ];
    }
}
