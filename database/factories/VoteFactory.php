<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'agenda_id' => Agenda::factory(),
            'user_id' => User::factory(),
            'vote_value' => $this->faker->randomElement(['yes', 'no', 'for', 'against', 'abstain']),
            'votes_cast' => $this->faker->numberBetween(1, 1000),
            'voted_at' => now(),
        ];
    }
}
