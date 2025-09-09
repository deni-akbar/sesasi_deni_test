<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LeaveType;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'title',
        'content',
        'additional_file',
        'status',
        'processed_by',
        'comment',
        'submitted_at',
    ];
    protected $casts = [
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
