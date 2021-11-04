<?php

namespace Emmanuelpcg\Basics\Useful;

abstract class Constants
{
    protected static array $labels = [];

    public static function getConstants() : array
    {
        $reflectionClass = new \ReflectionClass(static::class);

        return $reflectionClass->getConstants();
    }

    public static function toLabel($value) : ?array
    {
        $constantsFlip = array_flip(self::getConstants());

        if(isset($constantsFlip[$value]) && isset(static::$labels[$constantsFlip[$value]]))
            return static::$labels[$constantsFlip[$value]];

        return NULL;
    }

    public static function getValues(): array
    {
        return array_values(self::getConstants());
    }

    public static function hasValue($value) : bool
    {
        return in_array($value, self::getConstants(), true);
    }

    public static function hasKey(string $key) : bool
    {
        return isset(self::getConstants()[$key]);
    }

    public static function byKey(string $key)
    {
        $constants = self::getConstants();

        return $constants[$key] ?? null;
    }

    public static function toSelectOptions(): array
    {
        $options = array();

        foreach(self::getConstants() as $key => $value)
            $options[] = (object)['value' => $value, 'text' => self::toLabel($value) ?: $key];

        return $options;
    }
}