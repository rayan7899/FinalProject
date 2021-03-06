<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundOrder extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
