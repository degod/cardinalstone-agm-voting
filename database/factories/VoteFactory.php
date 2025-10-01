<?php

namespace Database\Factories;

use App\Enums\ItemStatuses;
use App\Enums\ShareholderStatuses;
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
        static $pairs = [];

        $initialArr = [
            'voted_at' => now(),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        $maxAttempts = 10;
        $attempts = 0;
        do {
            $agenda = Agenda::where('is_active', ItemStatuses::ACTIVE)->inRandomOrder()->first() ?? Agenda::factory()->create(['is_active' => true]);
            $companyId = $agenda->agm->company_id;

            $voteValues = explode('_', $agenda->voting_type);

            // Find a user who is a shareholder of this company
            $shareholder = Shareholder::where('company_id', $companyId)
                ->where('is_active', ShareholderStatuses::ACTIVE)
                ->inRandomOrder()
                ->first();

            // If no active shareholder exists, create one (and a user for it)
            if (!$shareholder) {
                $shareholder = Shareholder::factory()->create([
                    'company_id' => $companyId,
                    'is_active' => ShareholderStatuses::ACTIVE
                ]);
            }

            $user = $shareholder->user;
            $agendaId = $agenda->id;
            $userId = $user->id;
            $sharesOwned = $user->sharesForCompany($companyId)->shares_owned;
            $key = $agendaId . '-' . $userId;
            $attempts++;
        } while (in_array($key, array_column($pairs, 'key')) && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            // Try to find another agenda and restart the process
            $otherAgenda = Agenda::where('id', '!=', $agendaId)
                ->where('is_active', ItemStatuses::ACTIVE)
                ->inRandomOrder()
                ->first();
            if ($otherAgenda) {
                // Recursively try with the new agenda
                return $this->getValidPair($pairs);
            } else {
                // If no other agenda is found, fallback to previous behavior
                throw new \Exception('Could not find unique (agenda_id, user_id) pair after ' . $maxAttempts . ' attempts and no alternative agenda found. Please ensure enough shareholders and agendas exist for each company.');
            }
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
            'vote_value' => $this->faker->randomElement($voteValues),
        ];
    }
}
