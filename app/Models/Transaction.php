<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'users_id',

        'address',
        'payment',
        'total_price',
        'shipping_price',
        'status',
    ];

    /**
     * belongTo
     * Product User
     */
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

     /**
     * hasmany
     * Detail Transaction
     */
    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class, 'transactions_id', 'id');
    }
}
