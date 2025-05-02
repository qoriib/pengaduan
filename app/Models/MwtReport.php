<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MwtReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_date',
        'dialogues',
        'positive_findings',
        'unsafe_conditions',
        'documentation_path',
        'acknowledged_by',
        'approved_by',
        'acknowledged_by_qr_code_content',
        'approved_by_qr_code_content',
    ];

    protected $casts = [
        'execution_date' => 'date',
        'dialogues' => 'array',
        'positive_findings' => 'array',
        'unsafe_conditions' => 'array',
        'acknowledged_by_qr_code_content' => 'array',
        'approved_by_qr_code_content' => 'array',
    ];

    public function acknowledger()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function participants()
    {
        return $this->hasMany(MwtParticipant::class);
    }
}
