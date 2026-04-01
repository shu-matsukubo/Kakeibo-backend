<?php

namespace App\Http\Resources;


class ExpenseResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'point_amount' => $this->point_amount,
            'category_name' => $this->category?->name,
            'payment_method_name' => $this->paymentMethod?->name,
            'date' => $this->date,
        ];
    }
}
