<?php

namespace App\Enums;

class ItemStatuses
{
    const ACTIVE = true;
    const INACTIVE = false;

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
