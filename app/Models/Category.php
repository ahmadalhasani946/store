<?php

namespace App\Models;

use App\Http\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, ImageTrait;

    protected $fillable = ['name','description','depth','parent_id'];

    public static $folderName = 'categories';

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->with('categories');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return string
     */
    public function getImagePathAttribute(): string
    {
        if ($this->image) {
            return asset("storage/images/" . self::$folderName . '/' . $this->image->saved_name);
        }

        return '';
    }
}
