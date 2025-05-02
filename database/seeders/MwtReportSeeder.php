<?php

namespace Database\Seeders;

use App\Helpers\QrSignatureHelper;
use App\Models\MwtParticipant;
use App\Models\MwtReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class MwtReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $itmUsers = $users->where('role', 'ITM');

        // Buat 5 laporan MWT
        MwtReport::factory(20)->create()->each(function ($report) use ($users, $itmUsers) {
            // Pilih acknowledged_by secara acak
            $acknowledgedUser = $users->random();

            // Default update hanya acknowledged
            $report->update([
                'acknowledged_by_qr_code_content' => QrSignatureHelper::generateForMWT($report->id, $acknowledgedUser, 'acknowledged'),
            ]);

            // 50% kemungkinan laporan ini langsung diapprove juga
            if (fake()->boolean(50) && $itmUsers->count() > 0) {
                $approvedUser = $itmUsers->random();

                $report->update([
                    'approved_by' => $approvedUser->id,
                    'approved_by_qr_code_content' => QrSignatureHelper::generateForMWT($report->id, $approvedUser, 'approved'),
                ]);
            }

            // Tambahkan peserta (antara 2-5 user per laporan)
            $participants = $users->random(rand(2, 5));
            foreach ($participants as $user) {
                MwtParticipant::factory()->create([
                    'mwt_report_id' => $report->id,
                    'user_id' => $user->id,
                    'qr_code_content' => QrSignatureHelper::generateForMWT($report->id, $user, 'participant'),
                ]);
            }
        });
    }
}
