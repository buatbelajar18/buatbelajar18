<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['commission_id', 'payment_method', 'amount', 'payment_status', 'payment_date'];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }
}
