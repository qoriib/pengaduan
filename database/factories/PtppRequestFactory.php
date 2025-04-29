<?php

namespace Database\Factories;

use App\Models\PtppRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PtppRequest>
 */
class PtppRequestFactory extends Factory
{
    protected $model = PtppRequest::class;

    public function definition(): array
    {
        $fromUser = User::inRandomOrder()->first();
        $toUser = User::where('id', '!=', $fromUser->id)->inRandomOrder()->first();

        return [
            'no_form' => 'PND646000/' . now()->format('Y'),
            'request_date' => $this->faker->date(),
            'from_user_id' => $fromUser->id,
            'to_user_id' => $toUser->id,
            'area_location' => $this->faker->city(),
            'source_of_nonconformity' => json_encode($this->faker->randomElements(['Audit', 'Keluhan Pelanggan', 'Tinjauan Manajemen', 'Survey Pelanggan', 'Usulan atau Saran'])),
            'nonconformity_description' => $this->faker->paragraph(),
            'requirement_violated' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['Temuan', 'Observasi']),
            'due_date' => $this->faker->date(),
            'illustration_photo_path' => null,
            'status' => 'waiting_itm_initial_review',
        ];
    }
}
