<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Support\Str;

trait EnumHelperTrait
{
    /**
     * @param $enum
     * @return array
     */
    public function getNameArrayOld($enum): array
    {
        $arr = [];
        foreach ($enum::cases() as $item) {
            $arr[] = $item->value;
        }
        return $arr;
    }

    /**
     * @return array
     */
    public static function getValueArray(): array
    {
        $arr = [];
        foreach (self::cases() as $item) {
            $arr[] = $item->value;
        }
        return $arr;
    }

    /**
     * @return array
     */
    public static function getNameArray(): array
    {
        $arr = [];
        foreach (self::cases() as $item) {
            $arr[] = $item->name;
        }
        return $arr;
    }

    public static function getId($enum)
    {
        $id = 1;
        foreach (self::cases() as $item) {
            if ($enum->value == $item->value) return $id;
            $id++;
        }
        return '-1';
    }

    public static function getValue($name)
    {
        foreach (self::cases() as $item) {
            if ($item->name == $name) return $item->value;
        }
        return '-1';
    }
    public static function getName($id)
    {
        foreach (self::cases() as $item) {
            if ($item->value == $id) return $item->name;
        }
        return '-1';
    }
    public static function transformAsModel()
    {
        $res = [];
        foreach (self::cases() as $item) {
            $res[] = [
                'id' => $item->value,
                'name' => Str::of($item->name)
                    ->lower()
                    ->replace('_', ' ')
                    ->title(),
            ];
        }
        return $res;
    }

    public static function getNameLower($val)
    {
        foreach (self::cases() as $item) {
            if ($item->value == $val) return Str::of($item->name)
                ->lower()
                ->replace('_', ' ')
                ->title();
        }
        return '-1';
    }
}
