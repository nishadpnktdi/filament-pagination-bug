<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'user_id',
        'store_id',
        'entry_by_user',
        'salesman_id',
        'register_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function entryByUser()
    {
        return $this->belongsTo(User::class, 'entry_by_user');
    }

    public function salesman()
    {
        return $this->belongsTo(Staff::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($customer) {
             $customer->user()->delete();
        });
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
