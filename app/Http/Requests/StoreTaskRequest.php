<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_id' => [],
            'task_list_id' => [],
            'assignee_id' => [],
            'tags' => [],
            'name' => [],
            'description' => [],
            'priority' => [],
            'time' => [],
            'time_estimation' => [],
            'due_date' => []
        ];
    }
}
