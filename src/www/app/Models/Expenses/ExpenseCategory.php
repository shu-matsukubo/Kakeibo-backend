<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use HasUlids;
    use SoftDeletes;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'sort_order',
        'is_active',
    ];

    /*
    * 支払い履歴とのリレーション
    */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
