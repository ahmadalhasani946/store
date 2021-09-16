<?php

namespace App\Http\Traits;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

trait ImageTrait {

    public static function uploadImage($file, $path, $is_primary){
        $image = new Image();

        $image->is_primary = $is_primary;
        $size = $file->getSize();

        $filename = pathinfo($file, PATHINFO_FILENAME);
        $name = $filename . '.' . $file->getClientOriginalExtension();

        $originalName = $file->getClientOriginalName();
        $file->storeAs('/images/'.$path,  $name,'public');

        $image->original_name = $originalName;
        $image->saved_name = $name;
        $image->size = $size;

        return $image;
    }

    public static function deleteImage($imageName, $path){
        $fullPath = '/images/' . $path . '/';
        Storage::disk('public')->delete($fullPath . $imageName);
    }

}
