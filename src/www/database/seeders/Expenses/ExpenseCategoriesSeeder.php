<?php

namespace Database\Seeders\Expenses;

use App\Models\Expenses\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            '食費',
            '雑費',
            '固定費',
            '交際費',
            '医療費',
            '光熱費',
            '家賃',
            '交通費',
            'その他',
        ])->each(function ($name, $index) {
            ExpenseCategory::create([
                'name' => $name,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
