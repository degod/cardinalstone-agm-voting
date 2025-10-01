<?php

namespace Database\Factories;

use App\Enums\VoteValues;
use App\Models\Vote;
use App\Models\Agenda;
use App\Models\Shareholder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        $voteValues = array_keys(VoteValues::asKeyValue());
        static $pairs = [];

        $initialArr = [
            'vote_value' => $this->faker->randomElement($voteValues),
            'voted_at' => now(),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        $maxAttempts = 10;
        $attempts = 0;
        do {
            $agenda = Agenda::inRandomOrder()->first() ?? Agenda::factory()->create();
            $companyId = $agenda->agm->company_id;

            // Find a user who is a shareholder of this company
            $shareholder = Shareholder::where('company_id', $companyId)
                ->inRandomOrder()
                ->first();

            // If no shareholder exists, create one (and a user for it)
            if (!$shareholder) {
                $shareholder = Shareholder::factory()->create(['company_id' => $companyId]);
            }

            $user = $shareholder->user;
            $agendaId = $agenda->id;
            $userId = $user->id;
            $sharesOwned = $user->sharesForCompany($companyId)->shares_owned;
            $key = $agendaId . '-' . $userId;
            $attempts++;
        } while (in_array($key, array_column($pairs, 'key')) && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            throw new \Exception('Could not find unique (agenda_id, user_id) pair after ' . $maxAttempts . ' attempts. Please ensure enough shareholders exist for each company.');
        }

        $pairs[] = [
            'key' => $key,
            'agenda_id' => $agendaId,
            'user_id' => $userId,
        ];

        return [
            'agenda_id' => $agendaId,
            'user_id' => $userId,
            'votes_cast' => $sharesOwned,
        ];
    }
}
