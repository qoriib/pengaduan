<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MwtParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'mwt_report_id',
        'user_id',
        'qr_code_content',
    ];

    protected $casts = [
        'qr_code_content' => 'array',
    ];

    // Relasi ke laporan MWT
    public function report()
    {
        return $this->belongsTo(MwtReport::class, 'mwt_report_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
