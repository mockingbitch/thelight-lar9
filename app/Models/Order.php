<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'waiter_id',
        'guest_id',
        'table_id',
        'total',
        'note',
        'status'
    ];

    public function orderDetails()
    {
        return $this->hasMany(\App\Models\OrderDetail::class);
    }

    public function table()
    {
        return $this->belongsTo(\App\Models\Table::class, 'table_id');
    }

    public function waiter()
    {
        return $this->belongsTo(\App\Models\User::class, 'waiter_id');
    }
}
