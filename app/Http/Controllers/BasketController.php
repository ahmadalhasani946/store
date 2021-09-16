<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Option;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BasketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Basket::where('customer_id',Auth::user()->id)->get();
            $dataTable = Datatables::of($data)
                ->addColumn('image',function($row){
                    $product = Product::find($row->product_id);
                    $image = '<img src="' . $product->ImagePath . '" border="0" width="150" class="img-rounded" alt="' . $product->ImageAlt . '" align="center" />';
                    return $image;
                });
            return $this->actionButtons($request,$dataTable)->rawColumns(['action','image'])->make('true');
        }
    }

    public function actionButtons(Request $request,$dataTable){
        $dataTable->addColumn('action',function($row){
            $button = '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
            return $button;
        });
        return $dataTable;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $product = Product::find($request->product);
        $options = null;
        $variants = null;
        $optionVariantsList = array();
        $description = "product: " . $product->name . ", Quantity: " . $request->quantity;

        if ($request->options != null){
            $options = $request->options;
            $variants = $request->variants;
            $description .= ", ";
            for($i = 0; $i < count($options); $i++){
                $option = Option::where('name',$options[$i])->first();
                $variant = Variant::where('name',$variants[$i])->first();
                $description .= $variants[$i] . ": " . $options[$i];
                if($i < count($options) - 1){
                    $description .= ", ";
                }
                array_push($optionVariantsList,$product->optionVariant()->where('option_id',$option->id)->where('variant_id',$variant->id)->first()->pivot->id);
            }
        }

        $optionVariantsList = implode(',', $optionVariantsList);
        $basket = Basket::where('order_variant_products', $optionVariantsList)->where('product_id', $request->product)->where('customer_id', $request->customer)->first();

        if (!$basket){
            $basket = new Basket();
        }

        $basket = $this->StoreOrUpdateBasket($request, $basket, $description, $optionVariantsList);


        $ids = array();
        $descriptions = array();
        $images = array();
        $imageAlts = array();
        $quantities = array();

        $this->GetBasketContent($basket->customer_id,$ids,$descriptions,$images,$imageAlts,$quantities);

        $contents = Basket::where('customer_id', $request->customer)->count('*');

        return response()->json(['success'=>'Added Successfully', 'contents' => $contents, 'ids' => $ids , 'orderDescriptions' => $descriptions, 'productImages' => $images, 'alts' => $imageAlts, 'quantities' => $quantities]);
    }

    public function GetBasketContent($user,&$ids,&$descriptions,&$images,&$imageAlts,&$quantities){
        $contents = Basket::where('customer_id', $user)->get();
        foreach ($contents as $content) {
            array_push($ids,$content->id);
            array_push($descriptions,$content->description);
            array_push($images,Product::where('id', $content->product_id)->first()->ImagePath);
            array_push($imageAlts,Product::where('id', $content->product_id)->first()->ImageAlt);
            array_push($quantities,$content->quantity);
        }

    }

    public function StoreOrUpdateBasket(Request $request,Basket $basket, $description, $optionVariantsList){
        $basket->customer_id = $request->customer;
        $basket->product_id = $request->product;
        $basket->order_variant_products = $optionVariantsList;
        $basket->quantity = $request->quantity;
        $basket->description = $description;

        $basket->save();

        return $basket;
    }

    public static function getBasketInfo($basket,&$images,&$ids,&$descriptions)
    {
        foreach($basket as $record){
            $product_image = Product::find($record->product_id)->image()->where('is_primary',1)->first();
            array_push($images,$product_image);
            array_push($ids,$record->id);
            array_push($descriptions,$record->description);
        }
    }

    public function show(Request $request,$id)
    {
        $ids = array();
        $descriptions = array();
        $images = array();
        $imageAlts = array();
        $quantities = array();

        $this->GetBasketContent($request->id,$ids,$descriptions,$images,$imageAlts,$quantities);

        return response()->json(['success'=>'Added Successfully', 'ids' => $ids , 'orderDescriptions' => $descriptions, 'productImages' => $images, 'alts' => $imageAlts, 'quantities' => $quantities]);

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Basket $basket)
    {
        $basket->quantity = $request->quantity;
        $basket->save();

        $total = round($basket->quantity * $basket->product->price, 2);
        $basketTotal = $this->getBasketTotal();

        return response()->json(['data' => 'successfully Updated' , 'total' => $total, 'basketTotal' => $basketTotal]);
    }

    public function destroy(Basket $basket)
    {
        $basket->delete();

        if(str_contains(url()->previous(),'basket')){
            return back();
        }

        return response()->json(['success'=>'Added Successfully']);
    }

    public static function getBasketTotal(){
        $basketTotal = 0;

        foreach (auth()->user()->baskets as $record){
            $basketTotal += $record->product->price * $record->quantity;
        }

        return round($basketTotal , 2);
    }
}
