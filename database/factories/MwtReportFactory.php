<?php

namespace Database\Factories;

use App\Helpers\QrSignatureHelper;
use App\Models\MwtReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MwtReport>
 */
class MwtReportFactory extends Factory
{
    protected $model = MwtReport::class;

    public function definition(): array
    {
        $users = User::all();
        $acknowledgedUser = $users->random();

        return [
            'execution_date' => $this->faker->date(),
            'dialogues' => $this->faker->randomElements([
                'Diskusi keselamatan kerja',
                'Pentingnya penggunaan APD',
                'Pemeliharaan alat berat',
            ], rand(1, 3)),
            'positive_findings' => $this->faker->randomElements([
                'Area kerja bersih',
                'Pekerja menggunakan APD lengkap',
                'Rambu keselamatan terlihat jelas',
            ], rand(1, 3)),
            'unsafe_conditions' => $this->faker->randomElements([
                'Kabel listrik tergeletak',
                'Alat berat tanpa pengaman',
                'APAR tidak tersedia',
            ], rand(1, 3)),
            'acknowledged_by' => $acknowledgedUser->id,
        ];
    }
}
