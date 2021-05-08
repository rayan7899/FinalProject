<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function refund()
    {
        return $this->hasOne(RefundOrder::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class);
    }
}
