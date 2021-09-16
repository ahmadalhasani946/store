<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function variants()
    {
        return $this->belongsToMany(Variant::class,'option_variant')->withtimestamps()->withPivot('id');
    }
}
