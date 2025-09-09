<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'sometimes|in:revision,cancelled,approved,rejected',
        ];
    }
}
