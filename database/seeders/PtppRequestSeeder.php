<?php

namespace Database\Seeders;

use App\Models\PtppApproval;
use App\Models\PtppRequest;
use App\Models\PtppRequestDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Helpers\QrSignatureHelper;

class PtppRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itmUser = User::where('role', 'ITM')->first() ?? User::factory()->create(['role' => 'ITM']);

        PtppRequest::factory(20)->create()->each(function ($request) use ($itmUser) {
            $from = $request->fromUser;
            $to = $request->toUser;

            $stage = rand(1, 5); // Random tahap: 1 = hanya request, 5 = completed

            if ($stage >= 1) {
                PtppApproval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $itmUser->id,
                    'stage' => 'itm_initial_review',
                    'approved_at' => now(),
                    'qr_code_content' => QrSignatureHelper::generateForStage($request, $itmUser, 'itm_initial_review'),
                ]);
                $request->status = 'waiting_executor';
            }

            if ($stage >= 2) {
                PtppRequestDetail::factory()->create([
                    'request_id' => $request->id,
                    'resolver_user_id' => $to->id,
                ]);

                PtppApproval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $from->id,
                    'stage' => 'executor_response',
                    'approved_at' => now(),
                    'qr_code_content' => QrSignatureHelper::generateForStage($request, $from, 'executor_response'),
                ]);

                $request->status = 'waiting_requester_review';
            }

            if ($stage >= 3) {
                PtppApproval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $from->id,
                    'stage' => 'requester_review',
                    'approved_at' => now(),
                    'qr_code_content' => QrSignatureHelper::generateForStage($request, $from, 'requester_review'),
                ]);

                $request->status = 'waiting_itm_final_review';
            }

            if ($stage >= 4) {
                PtppApproval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $itmUser->id,
                    'stage' => 'itm_final_review',
                    'approved_at' => now(),
                    'verification_status' => 'Close',
                    'qr_code_content' => QrSignatureHelper::generateForStage($request, $itmUser, 'itm_final_review'),
                ]);

                $request->status = 'completed';
            }

            $request->save();
        });
    }
}
