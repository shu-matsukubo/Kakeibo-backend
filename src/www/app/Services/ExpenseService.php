<?php

namespace App\Services;

use App\Models\Expense;
use App\Support\DateUtil;

class ExpenseService
{

    /*
    * 指定された月の支払い情報を取得※未指定なら現在月
    */
    public function getMonthlySummary(?string $month)
    {
        // 範囲を指定
        $range = $month
            ? DateUtil::monthRange($month)
            : DateUtil::currentMonthRange();

        // 取得
        return Expense::whereBetween('date', [
            $range['start'],
            $range['end']
        ])->get();
    }
    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function delete(int $id)
    {
        return Expense::findOrFail($id)->delete();
    }
}
