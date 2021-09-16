<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionVariant extends Model
{
    use HasFactory;

    protected $table = 'option_variant';

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
