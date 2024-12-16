<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'description',
        'image',
    ];
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loves()
    {
        return $this->belongsToMany(User::class, 'commission_loves', 'commission_id', 'user_id')
                    ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // public function service()
    // {
    //     return $this->belongsTo(Service::class);
    // }

    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
