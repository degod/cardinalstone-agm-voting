<?php

namespace App\Enums;

class AgmStatuses
{
    const DRAFT = 'draft';
    const ACTIVE = 'active';
    const CLOSED = 'closed';
    const CANCELLED = 'cancelled';

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
