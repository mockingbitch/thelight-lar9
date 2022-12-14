<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bills';

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
        'status',
    ];

    public function waiter()
    {
        return $this->belongsTo(\App\Models\User::class, 'waiter_id');
    }

    public function table()
    {
        return $this->belongsTo(\App\Models\Table::class, 'table_id');
    }

    public function billDetails()
    {
        return $this->hasMany(\App\Models\BillDetail::class);
    }
}
