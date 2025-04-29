<?php

namespace Database\Factories;

use App\Models\PtppRequest;
use App\Models\RequestDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestDetail>
 */
class RequestDetailFactory extends Factory
{
    protected $model = RequestDetail::class;

    public function definition()
    {
        $resolver = User::inRandomOrder()->first();

        return [
            'request_id' => PtppRequest::factory(), // default jika tidak override
            'resolver_user_id' => $resolver->id,
            'received_at' => $this->faker->date(),
            'temporary_repair' => $this->faker->optional()->sentence(),
            'cause_analysis' => $this->faker->paragraph(2),
            'correction_action' => $this->faker->paragraph(2),
            'pic' => $this->faker->name(),
            'execution_time' => $this->faker->dateTimeBetween('+1 days', '+2 weeks'),
            'document_revised' => $this->faker->optional()->word(),
            'target_verification_date' => $this->faker->dateTimeBetween('+2 weeks', '+1 month'),
        ];
    }
}
