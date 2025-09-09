<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActOnLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'action' => 'required|in:approve,reject,revision',
            'comment' => 'required|string'
        ];
    }
}
