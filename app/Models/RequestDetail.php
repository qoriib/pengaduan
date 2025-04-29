<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    use HasFactory;

    protected $table = 'request_details';

    protected $fillable = [
        'request_id',
        'resolver_user_id',
        'received_at',
        'temporary_repair',
        'cause_analysis',
        'correction_action',
        'pic',
        'execution_time',
        'document_revised',
        'target_verification_date',
    ];

    protected $casts = [
        'received_at' => 'date',
        'execution_time' => 'date',
        'target_verification_date' => 'date',
    ];

    // Relasi
    public function request()
    {
        return $this->belongsTo(PtppRequest::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolver_user_id');
    }
}
