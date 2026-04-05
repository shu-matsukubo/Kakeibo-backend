<?php

namespace Database\Seeders\Expenses;

use App\Models\Expenses\ExpensePaymentMethod;
use Illuminate\Database\Seeder;

class ExpensePaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            '現金',
            'VPointPay',
            'PayPay',
            'Suica',
            'その他',
        ])->each(function ($name, $index) {
            ExpensePaymentMethod::create([
                'name' => $name,
                'sort_order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
