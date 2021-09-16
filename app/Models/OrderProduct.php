<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_product';

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function optionVariantProducts()
    {
        return $this->belongsToMany(OptionVariantProduct::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
