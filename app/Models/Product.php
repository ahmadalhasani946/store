<?php

namespace App\Models;

use App\Http\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, ImageTrait;

    protected $guarded = [];

    public static $folderName = 'products';

    public function optionVariant()
    {
        return $this->belongsToMany(OptionVariant::class)->withPivot('id');
    }

    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class,'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class);
    }

    public function getImagePathAttribute(): string
    {
        if ($this->image) {
            return asset("storage/images/" . self::$folderName . '/' . $this->image()->where('is_primary','1')->first()->saved_name);
        }
        return '';
    }

    public function getImageAltAttribute(): string
    {
        if ($this->image) {
            return $this->image()->where('is_primary','1')->first()->saved_name;
        }
        return '';
    }
}
