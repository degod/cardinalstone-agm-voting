<?php

namespace Database\Factories;

use App\Enums\ShareholderStatuses;
use App\Models\Shareholder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShareholderFactory extends Factory
{
    protected $model = Shareholder::class;

    public function definition(): array
    {
        static $pairs = [];

        $initialArr = [
            'shares_owned' => $this->faker->numberBetween(10, 5000),
            'share_certificate_number' => strtoupper($this->faker->bothify('CERT-#####')),
            'acquired_date' => $this->faker->date(),
            'is_active' => $this->faker->randomElement([ShareholderStatuses::ACTIVE, ShareholderStatuses::INACTIVE]),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        do {
            $company = Company::inRandomOrder()->first() ?? Company::factory()->create();
            $user = User::inRandomOrder()->first() ?? User::factory()->create();

            $pair = $user->id . '-' . $company->id;
        } while (in_array($pair, $pairs));

        $pairs[] = $pair;
        return [
            'company_id' => $company->id,
            'user_id' => $user->id,
        ];
    }
}
