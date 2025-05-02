<?php

namespace App\Helpers;

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

    /**
     * Generate QR signature content for a user involved in MWT.
     *
     * @param  int  $reportId
     * @param  \App\Models\User  $user
     * @param  string  $type  (acknowledged | approved | participant)
     * @return string  JSON QR content
     */
    public static function generateForMWT($reportId, $user, string $type): string
    {
        $data = [
            'report_id' => $reportId,
            'type' => $type,
            'name' => $user->name,
            'role' => $user->role,
            'signed_at' => now()->toDateTimeString(),
            'hash' => sha1($reportId . $user->id . $type . now()),
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
