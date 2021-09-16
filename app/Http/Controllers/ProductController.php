<?php

namespace App\Http\Controllers;

use App\Http\Traits\ImageTrait;
use App\Models\Image;
use App\Models\Option;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variant;
use App\Models\OptionVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    use ImageTrait;

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = null;
            if ($request->trash) {
                $data = Product::onlyTrashed();
            } else {
                $data = Product::all();
            }
            $dataTable = Datatables::of($data)
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->ImagePath . '" border="0" width="150" class="img-rounded" alt="' . $row->ImageAlt . '" align="center" />';
                    return $image;
                });
            return $this->actionButtons($request,$dataTable)->rawColumns(['action', 'image'])->make('true');
        }
        if (!$request->has('trash')) {
            return view('product.index');
        }
        return view('product.trashed');
    }

    public function actionButtons(Request $request, $dataTable){
        if (!$request->trash) {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-info" style="min-width:100px;" href="' . route("products.show", ["product" => $row]) . '"> Show </a>';
                $button .= '<a class="btn btn-success" style="min-width:100px;margin-left:10px;" href="' . route("products.edit", ["product" => $row]) . '"> Edit </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                return $button;
            });
        } else {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("products.restore", ["product" => $row->id]) . '"> Restore </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')"> Final Delete</button>';
                return $button;
            });
        }
        return $dataTable;
    }

    public function create()
    {
        $categories = Category::all();
        if (!$categories) {
            return redirect(route('categories.index'))->withErrors('You Have To Add Categories First To Be Able To Create A Product');
        }
        $variants = Variant::with('options')->get();
        return view('product.create', compact('categories', 'variants'));
    }

    public function storeOrUpdateRecord(Request $request, $product)
    {
        $product = $product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        if ($request->has('categories')) {
            $categories = $request->categories;
            foreach ($categories as $categoryID) {
                $category = Category::find($categoryID);
                $product->categories()->attach($category);
            }
        }

        $variants = Variant::with('options')->get();
        foreach ($variants as $variant) {
            if ($request->has($variant->name)) {
                foreach ($variant->options as $option) {
                    $options = $request[$variant->name];
                    foreach ($options as $optionToAdd) {
                        if ($optionToAdd == $option->id) {
                            $optionVariant = OptionVariant::where('variant_id', '=', $variant->id)
                                ->where('option_id', '=', $optionToAdd)
                                ->get();
                            $optionVariant = $optionVariant[0];
                            $product->optionVariant()->attach($optionVariant);
                        }
                    }
                }
            }
        }
        return $product;
    }

    public function store(StoreProductRequest  $request)
    {
        $validated = $request->validated();

        $product = new Product();

        $product = $this->storeOrUpdateRecord($request, $product);
        $image = Product::uploadImage($request->file('primary'), Product::$folderName, 1);
        $product->image()->save($image);

        $images = $request->file('images');
        foreach ($images as $image) {
            $imageToSave = Product::uploadImage($image, Product::$folderName, 0);
            $product->image()->save($imageToSave);
        }

        return redirect(route('products.index'))->with('message', 'The Product was Created to Successfully. ');
    }

    public function show(Product $product)
    {
        $optionVariant = $product->optionVariant()->get()->groupBy('variant_id');
        $optionVariant = $this->GetVariantsOptionsToShow($optionVariant);
        $categories = $product->categories()->pluck('name')->toArray();
        $categories = implode(',', $categories);

        $images = $product->image;

        return view('product.show', compact('product', 'optionVariant', 'categories', 'images'));
    }

    public function GetVariantsOptionsToShow($optionVariant){
        $optVarList = "";
        $variants = $optionVariant->keys();
        foreach ($variants as $var){
            $variant = Variant::withTrashed()->find($var);
            $count = 1;
            $optVarList .= $variant->name . " : ";
            $OptionVariants = $optionVariant[$var];
            $total = $OptionVariants->count();
            foreach ($OptionVariants as $OptionVariant){
                $option = Option::withTrashed()->find($OptionVariant->option_id);
                $optVarList .= $option->name;
                if ($count < $total){
                    $optVarList .= ", ";
                }
                $count++;
            }
            $optVarList .= "<br>";
        }

        return $optVarList;
    }

    public function edit(Product $product)
    {
        $optionVariants = $product->optionVariant()->pluck('option_id')->toArray();
        $variants = Variant::with('options')->get();

        $productCategories = $product->categories()->pluck('category_id')->toArray();
        $categories = Category::all();

        return view('product.edit', compact('optionVariants', 'variants', 'product', 'productCategories', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            "name" => [
                'required',
                Rule::unique('products')->ignore($product->name, 'name'),
                'max:255'
            ],
            "description" => "required",
            "price" => 'required|numeric',
            "categories" => 'required',
            "primary" => "image|mimes:jpeg,png,jpg",
            "images.*" => "image|mimes:jpeg,png,jpg,jfif",
        ]);

        if ($request->has('categories')) {
            $this->deleteCategories($product);
        }

        $this->deleteOptionsVariants($product);

        $product = $this->storeOrUpdateRecord($request, $product);

        if ($request->has('primary')) {

            $image = $product->image()->where('is_primary', '=', '1')->first();
            Product::deleteImage($image->saved_name, Product::$folderName);
            $product->image()->where('is_primary', '=', 1)->delete();

            $image = Product::uploadImage($request->file('primary'), Product::$folderName, 1);
            $product->image()->save($image);
        }

        if ($request->has('images')) {
            $images = $product->image()->where('is_primary', '=', '0')->get();
            foreach ($images as $image) {
                Product::deleteImage($image->saved_name, Product::$folderName);
                $product->image()->where('saved_name', '=', $image->saved_name)->delete();
            }

            $images = $request->file('images');
            foreach ($images as $image) {
                $imageToSave = Product::uploadImage($image, Product::$folderName, 0);
                $product->image()->save($imageToSave);
            }
        }

        return redirect(route('products.index'))->with('message', 'The Product was Updated to Successfully. ');
    }

    public function destroy(Product $product)
    {
        foreach ($product->orderProducts as $orderProduct) {
            $orderProduct->delete();
        }

        $product->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($product)
    {
        $product = Product::onlyTrashed()->where('id', $product)->first();

        $reviews = $product->reviews;
        foreach ($reviews as $review) {
            $review->forceDelete();
        }

        $images = $product->image;
        foreach ($images as $image) {
            Product::deleteImage($image->saved_name, Product::$folderName);
            $product->image()->where('saved_name', '=', $image->saved_name)->delete();
        }

        $this->deleteCategories($product);
        $this->deleteOptionsVariants($product);

        $product->forceDelete();

        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreProduct($product)
    {
        $product = Product::withTrashed()->where('id', $product)->first();
        $orderProducts = OrderProduct::withTrashed()->where('product_id', $product->id)->get();

        foreach ($orderProducts as $orderProduct){
            $orderProduct->restore();
        }

        $product->restore();
        return redirect()->back()->with("message", "The Record Was Restored");
    }

    public function deleteCategories(Product $product)
    {
        $categoryProducts = $product->categories()->get();
        foreach ($categoryProducts as $categoryProduct) {
            $product->categories()->detach($categoryProduct);
        }
    }

    public function deleteOptionsVariants(Product $product)
    {
        $optionVariantProducts = $product->optionVariant()->get();
        foreach ($optionVariantProducts as $optionVariantProduct) {
            $product->optionVariant()->detach($optionVariantProduct);
        }
    }

}
