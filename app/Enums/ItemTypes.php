<?php

namespace App\Enums;

class ItemTypes
{
    const RESOLUTION = 'resolution';
    const ELECTION = 'election';
    const APPROVAL = 'approval';
    const OTHER = 'other';

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
