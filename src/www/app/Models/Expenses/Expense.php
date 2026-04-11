<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'amount',
    'point_amount',
    'payment_method_id',
    'category_id',
    'memo',
    'date',
])]
class Expense extends BaseModel
{
    use SoftDeletes;

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'date' => 'immutable_date',
            'deleted_at' => 'immutable_date',
        ]);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(ExpensePaymentMethod::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
