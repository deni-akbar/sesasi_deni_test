<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'leave_type_id' => 'required|int',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'additional_file' => 'nullable|file|max:2048',
        ];
    }
}
