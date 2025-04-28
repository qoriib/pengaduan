<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'approver_user_id',
        'stage',
        'approved_at',
        'qr_code_path',
        'verification_status',
        'next_verification_target',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'next_verification_target' => 'date',
    ];

    // Relasi
    public function request()
    {
        return $this->belongsTo(PtppRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }
}
