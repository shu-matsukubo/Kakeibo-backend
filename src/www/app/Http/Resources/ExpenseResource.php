<?php

namespace App\Http\Resources;


class ExpenseResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'category' => $this->category?->name,
            'payment_method' => $this->paymentMethod?->name,
            'date' => $this->date,
        ];
    }
}
