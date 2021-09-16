<?php

namespace App\Models;

use App\Http\Traits\ImageTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, ImageTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone'
    ];

    public static $folderName = 'users';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class,'customer_id','id');
    }

    public function orderProducts()
    {
        return $this->hasManyThrough(OrderProduct::class, Order::class, 'customer_id', 'order_id');
    }

    public function getImagePathAttribute(): string
    {
        if ($this->image) {
            return asset("storage/images/" . self::$folderName . '/' . $this->image->saved_name);
        }

        return asset("storage/images/" . self::$folderName . "/avatar.png");
    }

    public function getImageAltAttribute(): string
    {
        if ($this->image) {
            return $this->image->saved_name;
        }

        return '';
    }
}
