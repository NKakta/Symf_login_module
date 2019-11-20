<?php

declare(strict_types=1);

namespace App\Enum;

final class Regions
{
    const EUROPE_WEST = 'Europe West';
    const NORTH_AMERICA = 'North America';
    const EUROPE_NORDIC_EAST = 'Europe Nordic East';
    const BRAZIL = 'Brazil';
    const LAS = 'LAS';
    const LAN = 'LAN';
    const RUSSIA = 'Russia';
    const TURKEY = 'Turkey';
    const OCEANIA = 'Oceania';

    public static function getAll(): array
    {
        return [
            self::EUROPE_WEST,
            self::NORTH_AMERICA,
            self::EUROPE_NORDIC_EAST,
            self::BRAZIL,
            self::LAS,
            self::LAN,
            self::RUSSIA,
            self::TURKEY,
            self::OCEANIA,

        ];
    }

    public static function getChoices(): array
    {
        return [
            self::EUROPE_WEST => 'europe_west',
            self::NORTH_AMERICA => 'north_america',
            self::EUROPE_NORDIC_EAST => 'europe_nordic_east',
            self::BRAZIL => 'brazil',
            self::LAS => 'las',
            self::LAN => 'lan',
            self::RUSSIA => 'russia',
            self::TURKEY => 'turkey',
            self::OCEANIA => 'oceania',

        ];
    }

}
