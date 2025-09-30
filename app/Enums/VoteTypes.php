<?php

namespace App\Enums;

class VoteTypes
{
    const YES_NO = 'yes_no';
    const FOR_AGAINST_ABSTAIN = 'for_against_abstain';

    public static function asKeyValue(): array
    {
        $result = [];
        $class = new \ReflectionClass(self::class);

        foreach ($class->getConstants() as $name => $value) {
            $result[$value] = $name;
        }

        return $result;
    }
}
