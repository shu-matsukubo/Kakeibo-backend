<?php

namespace App\Services\Expenses;

use App\Models\Expenses\ExpenseCategory;

class CategoryService
{
    /*
    * カテゴリ一覧を取得
    */
    public function list()
    {
        return ExpenseCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    * カテゴリを作成
    */
    public function create(array $data)
    {
        return ExpenseCategory::create($data);
    }

    /*
    * カテゴリを削除
    */
    public function delete(int $id)
    {
        return ExpenseCategory::findOrFail($id)->delete();
    }
}
