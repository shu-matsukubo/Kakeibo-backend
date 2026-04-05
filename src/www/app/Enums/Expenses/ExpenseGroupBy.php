<?php

namespace App\Enums\Expenses;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

enum ExpenseGroupBy: string
{
    case CATEGORY = 'category';
    case PAYMENT_METHOD = 'payment_method';
    case DATE = 'date';

    public static function fromRequest(?string $value): self
    {
        return self::tryFrom($value ?? '') ?? self::CATEGORY;
    }
}
