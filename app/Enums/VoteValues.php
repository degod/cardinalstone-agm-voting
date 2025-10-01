<?php

namespace App\Enums;

class VoteValues
{
    const YES = 'yes';
    const NO = 'no';
    const FOR = 'for';
    const AGAINST = 'against';
    const ABSTAIN = 'abstain';

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
