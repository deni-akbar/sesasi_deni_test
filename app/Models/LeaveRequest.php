<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'details',
        'status',
        'processed_by',
        'comment',
        'submitted_at',
    ];
    protected $casts = [
        'details' => 'array',
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
}
