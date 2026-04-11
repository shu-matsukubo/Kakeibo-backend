<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class DateUtil
{
    private const TZ = 'Asia/Tokyo';

    public static function now(): CarbonImmutable
    {
        return CarbonImmutable::now(self::TZ);
    }

    public static function toDateString(CarbonImmutable $date): string
    {
        return $date->toDateString();
    }

    public static function parseMonth(string $month): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('Y-m', $month, self::TZ);
    }

    public static function resolveMonth(?string $month): CarbonImmutable
    {
        return $month
            ? self::parseMonth($month)
            : self::now();
    }

    public static function startOfMonth(CarbonImmutable $date): CarbonImmutable
    {
        return $date->startOfMonth();
    }

    public static function endOfMonth(CarbonImmutable $date): CarbonImmutable
    {
        return $date->endOfMonth();
    }

    public static function monthRange(CarbonImmutable $date): array
    {
        return [
            'start' => self::startOfMonth($date),
            'end' => self::endOfMonth($date),
        ];
    }
}
