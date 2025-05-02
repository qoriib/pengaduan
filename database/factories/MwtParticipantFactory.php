<?php

namespace Database\Factories;

use App\Models\MwtParticipant;
use App\Models\MwtReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MwtParticipant>
 */
class MwtParticipantFactory extends Factory
{
    protected $model = MwtParticipant::class;

    public function definition(): array
    {
        return [
            'mwt_report_id' => MwtReport::factory(),
            'user_id' => User::factory(),
        ];
    }
}
