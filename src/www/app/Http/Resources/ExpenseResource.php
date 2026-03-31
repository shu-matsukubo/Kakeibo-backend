<?php

namespace App\Http\Resources;


class ExpenseResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => "{$this->amount}円",
            'category_name' => $this->category?->name,
            'payment_method_name' => $this->paymentMethod?->name,
            'date' => $this->date,
        ];
    }
}
