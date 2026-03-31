<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    /*
    * カテゴリ一覧を取得
    */
    public function list()
    {
        return Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /*
    * カテゴリを作成
    */
    public function create(array $data)
    {
        return Category::create($data);
    }

    /*
    * カテゴリを削除
    */
    public function delete(int $id)
    {
        return Category::findOrFail($id)->delete();
    }
}
