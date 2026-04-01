<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasUlids;
    use SoftDeletes;

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

    /*
    * 支払方法とのリレーション
    */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /*
    * カテゴリーとのリレーション
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
