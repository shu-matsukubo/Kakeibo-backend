<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Expenses\ExpenseGroupBy;

class Expense extends Model
{
    use HasUlids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'amount',
        'point_amount',
        'payment_method_id',
        'category_id',
        'memo',
        'date',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(ExpensePaymentMethod::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
