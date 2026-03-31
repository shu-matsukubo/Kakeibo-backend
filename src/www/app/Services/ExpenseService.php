<?php

namespace App\Services;

use App\Models\Expense;
use App\Support\DateUtil;

class ExpenseService
{
    /*
    * 指定された月の支払い情報を取得※未指定なら現在月
    */
    public function getMonthlySummary(string $month)
    {
        // 範囲を指定
        $range = DateUtil::monthRange($month);

        // 取得
        return Expense::whereBetween('date', [
            $range['start'],
            $range['end']
        ])->get();
    }

    /*
    * 支出を作成
    */
    public function create(array $data)
    {
        return Expense::create($data);
    }

    /*
    * 支出を削除
    */
    public function delete(int $id)
    {
        return Expense::findOrFail($id)->delete();
    }
}
