<?php

namespace App\Support;

use Carbon\Carbon;

class DateUtil
{
    /**
     * 2つのdatetimeが同じ月か比較
     */
    public static function isSameMonth($date1, $date2): bool
    {
        return Carbon::parse($date1)->format('Y-m') ===
            Carbon::parse($date2)->format('Y-m');
    }

    /**
     * 今月の開始日を取得
     */
    public static function startOfMonth($date)
    {
        return Carbon::parse($date)->startOfMonth();
    }

    /**
     * 今月の終了日を取得
     */
    public static function endOfMonth($date)
    {
        return Carbon::parse($date)->endOfMonth();
    }

    /**
     * 現在日時取得
     */
    public static function now(): Carbon
    {
        return Carbon::now();
    }

    /**
     * 今月の開始日と終了日
     */
    public static function currentMonthRange(): array
    {
        $now = Carbon::now();

        return [
            'start' => $now->copy()->startOfMonth(),
            'end' => $now->copy()->endOfMonth(),
        ];
    }

    /**
     * 指定月の開始日と終了日
     * $month: '2026-03' 形式想定
     */
    public static function monthRange(string $month): array
    {
        $date = Carbon::createFromFormat('Y-m', $month);
        \Log::info($date);

        return [
            'start' => $date->copy()->startOfMonth(),
            'end' => $date->copy()->endOfMonth(),
        ];
    }
}
