<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'products_id',
        'URL'
    ];

    /**
     * belongTo
     * Product
     * gak dipake
     */
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getAttribute($key)
    {
        // Jika ada isi custom attribute yang didefinisikan maka akan mengembalikannya
        return config('app.url'). Storage::url($key);
    }
}
