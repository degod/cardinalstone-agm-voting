<?php

namespace Database\Factories;

use App\Enums\AgmStatuses;
use App\Enums\ItemTypes;
use App\Enums\VoteTypes;
use App\Models\Agenda;
use App\Models\Agm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    public function definition(): array
    {
        $itemTypes = array_keys(ItemTypes::asKeyValue());
        $votingTypes = array_keys(VoteTypes::asKeyValue());

        static $pairs = [];

        $initialArr = [
            'item_number' => $this->faker->numberBetween(1, 20),
            'description' => $this->faker->paragraph(),
            'item_type'   => $this->faker->randomElement($itemTypes),
            'voting_type' => $this->faker->randomElement($votingTypes),
            'is_active'   => $this->faker->boolean(90),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        // Pick or create an AGM that is not closed or cancelled
        $agm = Agm::whereNotIn('status', [AgmStatuses::CLOSED, AgmStatuses::CANCELLED])->inRandomOrder()->first() ?? Agm::factory()->create(['status' => 'open']);

        // Reuse an agenda_uuid for this AGM 50% of the time, otherwise make new
        $existingForAgm = collect($pairs)
            ->filter(fn($p) => $p['agm_id'] === $agm->id)
            ->pluck('agenda_uuid')
            ->all();

        if ($existingForAgm && $this->faker->boolean(50)) {
            $agendaUuid = $this->faker->randomElement($existingForAgm);
        } else {
            $agendaUuid = (string) Str::uuid();
        }

        // Ensure unique title per (agm_id, agenda_uuid)
        do {
            $title = $this->faker->sentence(6);
            $key = $agm->id . '-' . $agendaUuid . '-' . $title;
        } while (in_array($key, array_column($pairs, 'key')));

        $pairs[] = [
            'key'         => $key,
            'agm_id'      => $agm->id,
            'agenda_uuid' => $agendaUuid,
        ];

        return [
            'agm_id'      => $agm->id,
            'agenda_uuid' => $agendaUuid,
            'title'       => $title,
        ];
    }
}
