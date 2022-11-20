<?php

namespace Sophokles\Graphql\Types;

class Types
{
    public static function type($name)
    {
        if (class_exists($name)) {
            return new $name();
        }
    }

    public static function floatReturn(array $values, string $fieldName): float
    {
        if (isset($values[$fieldName])) {
            return (float)$values[$fieldName];
        }
        return 0;
    }

    public static function intReturn(array $values, string $fieldName): int
    {
        if (isset($values[$fieldName])) {
            return (int)$values[$fieldName];
        }
        return 0;
    }

    public static function stringReturn(array $values, string $fieldName): string
    {
        if (isset($values[$fieldName])) {
            return (string)$values[$fieldName];
        }
        return '';
    }

    public static function booleanReturn(array $values, string $fieldName): bool
    {
        if (isset($values[$fieldName])) {
            return $values[$fieldName] === true;
        }
        return false;
    }

}
