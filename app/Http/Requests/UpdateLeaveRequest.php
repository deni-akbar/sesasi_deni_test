<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'leave_type_id' => 'sometimes|int',
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            // 'details' => 'nullable|array'
        ];
    }
}
