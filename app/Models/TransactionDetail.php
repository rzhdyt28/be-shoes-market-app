<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'users_id',
        'products_id',
        'transactions_id',

        'quantity',
    ];

     /**
     * belongTo
     * User
     */
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

     /**
     * belongTo
     * Product
     * public function product(){
     *    return $this->belongsTo(Product::class, 'products_id', 'id');
     *  }
     */

     /**
     * hasOne
     * Product
     */
    public function product(){
        return $this->hasOne(Product::class, 'id', 'products_id');
    }

     /**
     * belongTo
     * Transaction
     */
    public function transaction(){
        return $this->belongsTo(Transaction::class, 'transactions_id', 'id');
    }

}
