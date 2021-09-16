<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;


class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('single');
    }

    public function multi()
    {
        return view('multi');
    }

    /**
     *  Multi Images Uploader
     */
    public function create(Request $request)
    {
        $images = $request->file('multi');

        foreach ($images as $image){
            $size = $image->getSize();
            
            $referred_id = '1';        

            $referred_type = 'product';
            
            $originalName = $image->getClientOriginalName();
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $name = $filename . '.' . $image->getClientOriginalExtension();
            
            $is_primary = 0;

            $path = $image->move(public_path('/images/products'),$name);

            $image = new Image();

            $image->referred_id = $referred_id;
            $image->referred_type = $referred_type;
            $image->is_primary = $is_primary;
            $image->original_name = $originalName;
            $image->saved_name = $name;
            $image->size = $size; 
            
            $image->save();
        }
        

        return view('multi');
    }

    /**
     * Single Image Uploader
     */
    public function store(Request $request)
    {
        $image_count = Image::count();
        $CurrentID = 1;
        if($image_count != 0){
            $last_image = Image::first()->orderBy('id','desc')->get();
            $CurrentID = $last_image[0]->id + 1;
        }

        $primary = $request->file('primary');
        $size = $primary->getSize();
        $referred_id = '1';        
        $CurrentID = (string) $CurrentID;
        $saved_name = $CurrentID . '.' . $primary->getClientOriginalExtension();
        $referred_type = 'product';     
        $originalName = $primary->getClientOriginalName();
        $filename = pathinfo($primary, PATHINFO_FILENAME);
        $name = $filename . '.' . $primary->getClientOriginalExtension();
        
        $is_primary = 1;

        $path = $primary->move(public_path('/images/products'),$name);

        $image = new Image();

        $image->referred_id = $referred_id;
        $image->referred_type = $referred_type;
        $image->is_primary = $is_primary;
        $image->original_name = $originalName;
        $image->saved_name = $name;
        $image->size = $size; 
        
        $image->save();

        return view('single');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $images = Image::select('*')->where('referred_id',1)->get();

        dd($images);
    }
}
