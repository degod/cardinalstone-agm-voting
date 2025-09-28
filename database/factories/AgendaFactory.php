<?php

namespace Database\Factories;

use App\Enums\ItemTypes;
use App\Enums\VoteTypes;
use App\Models\Agenda;
use App\Models\Agm;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'item_type' => $this->faker->randomElement($itemTypes),
            'voting_type' => $this->faker->randomElement($votingTypes),
            'is_active' => $this->faker->boolean(90),
        ];

        return array_merge($initialArr, $this->getValidPair($pairs));
    }

    private function getValidPair(array &$pairs): array
    {
        do {
            $agm = Agm::inRandomOrder()->first() ?? Agm::factory()->create();
            $title = $this->faker->sentence(8);

            $pair = $agm->id . '-' . $title;
        } while (in_array($pair, $pairs));

        $pairs[] = $pair;
        return [
            'agm_id' => $agm,
            'title' => $title,
        ];
    }
}
