<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_verified',
        'phone',
        'meta'
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'meta' => 'array',
        'is_verified' => 'boolean',
        'email_verified_at' => 'datetime'
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
    // JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return ['role' => $this->role->name ?? 'user'];
    }
}
