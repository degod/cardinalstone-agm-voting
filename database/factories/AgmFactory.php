<?php

namespace Database\Factories;

use App\Enums\AgmStatuses;
use App\Models\Agm;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgmFactory extends Factory
{
    protected $model = Agm::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+1 week');
        $end = (clone $start)->modify('+1 hours');
        static $pairs = [];

        $initialArr = [
            'description' => $this->faker->paragraph(),
            'meeting_date' => $start,
            'voting_start_time' => $start,
            'voting_end_time' => $end,
            'status' => $this->faker->randomElement([AgmStatuses::DRAFT, AgmStatuses::ACTIVE, AgmStatuses::CLOSED, AgmStatuses::CANCELLED]),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        do {
            $company = Company::inRandomOrder()->first() ?? Company::factory()->create();
            $title = $this->faker->company() . ' ' . $this->faker->year() . ' AGM';

            $pair = $title . '-' . $company->id;
        } while (in_array($pair, $pairs));

        $pairs[] = $pair;
        return [
            'company_id' => $company,
            'title' => $title,
        ];
    }
}
