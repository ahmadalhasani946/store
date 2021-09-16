<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionVariantProduct extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'option_variant_product';

    public function orderProducts()
    {
        return $this->belongsToMany(OrderProduct::class,'id','option_variant_id');
    }
}
