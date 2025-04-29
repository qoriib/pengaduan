<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\PtppRequest;
use App\Models\RequestDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class PtppRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itmUser = User::where('role', 'ITM')->first() ?? User::factory()->create(['role' => 'ITM']);

        PtppRequest::factory(20)->create()->each(function ($request) use ($itmUser) {
            $from = $request->from_user_id;
            $to = $request->to_user_id;

            $stage = rand(1, 5); // Random tahap: 1 = hanya request, 5 = completed

            if ($stage >= 1) {
                // ITM initial approval
                Approval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $itmUser->id,
                    'stage' => 'itm_initial_review',
                    'approved_at' => now(),
                ]);
                $request->status = 'waiting_executor';
            }

            if ($stage >= 2) {
                // Executor mengisi detail
                RequestDetail::factory()->create([
                    'request_id' => $request->id,
                    'resolver_user_id' => $to,
                ]);
                $request->status = 'waiting_requester_review';

                Approval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $from,
                    'stage' => 'executor_response',
                    'approved_at' => now(),
                ]);
            }

            if ($stage >= 3) {
                // Requester review
                Approval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $from,
                    'stage' => 'requester_review',
                    'approved_at' => now(),
                ]);
                $request->status = 'waiting_itm_final_review';
            }

            if ($stage >= 4) {
                // Final approval ITM
                Approval::create([
                    'request_id' => $request->id,
                    'approver_user_id' => $itmUser->id,
                    'stage' => 'itm_final_review',
                    'approved_at' => now(),
                ]);
                $request->status = 'completed';
            }

            $request->save();
        });
    }
}
