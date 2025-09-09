<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'verified' => 'sometimes|nullable|in:1,0',
        ];
    }
}
