<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtppRequest extends Model
{
    use HasFactory;

    protected $table = 'ptpp_requests';

    protected $fillable = [
        'no_form',
        'request_date',
        'from_user_id',
        'to_user_id',
        'area_location',
        'source_of_nonconformity',
        'nonconformity_description',
        'requirement_violated',
        'category',
        'due_date',
        'illustration_photo_path',
        'status',
    ];

    protected $casts = [
        'source_of_nonconformity' => 'array', // JSON otomatis array
        'request_date' => 'date',
        'due_date' => 'date',
    ];

    // Relasi
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function approvals()
    {
        return $this->hasMany(PtppApproval::class, 'request_id');
    }

    public function requestDetail()
    {
        return $this->hasOne(PtppRequestDetail::class, 'request_id');
    }
}
