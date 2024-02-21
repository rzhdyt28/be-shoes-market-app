<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'price',
        'description',
        'tags',

        'product_categories_id',
    ];

     /**
     * hasmany
     * Product Gallery
     */
    public function product_galleries(){
        return $this->hasMany(ProductGallery::class, 'products_id', 'id');
    }

    /**
     * belongTo
     * Product Category
     */
    public function product_category(){
        return $this->belongsTo(ProductCategory::class, 'product_categories_id', 'id');
    }
    
    /**
     * hasmany
     * Product Detail
     */
    public function transcation_details(){
        return $this->hasMany(TransactionDetail::class, 'products_id', 'id');
    }
}
