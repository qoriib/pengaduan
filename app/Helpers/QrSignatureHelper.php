<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class QrSignatureHelper
{
    /**
     * Generate QR signature for a specific approval stage.
     *
     * @param  \App\Models\PtppRequest  $request
     * @param  \App\Models\User  $user
     * @param  string  $stage
     * @return string  path to saved QR image (public)
     */
    public static function generateForStage($request, $user, string $stage)
    {
        $executor = optional($request->requestDetail?->resolver)->name;

        $data = [
            'request_id' => $request->id,
            'stage' => $stage,
            'approved_by' => $user->name,
            'role' => $user->role,
            'executor' => $executor,
            'approved_at' => now()->toDateTimeString(),
            'hash' => sha1($request->id . $user->id . $stage . now()),
        ];

        $qrContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $qrContent;
    }
}
