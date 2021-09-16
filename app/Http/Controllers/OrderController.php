<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Option;
use App\Models\OptionVariant;
use App\Models\OptionVariantProduct;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Notifications\OrderArrived;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Psy\Util\Json;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $order = OrderProduct::all()->sortBy('created_at')->first();
        if ($request->ajax()) {
            $data = $request->trash ? Order::onlyTrashed() : Order::all();
            $dataTable = Datatables::of($data)
                         ->addColumn('customer',function($row){
                         $customer = User::withTrashed()->where('id',$row->customer_id)->first();
                         return $customer->name;
                        })
                        ->addColumn('done',function($row){
                            return $row->done ? 'Yes' : 'No';
                        });

            return $this->actionButtons($request,$dataTable)->rawColumns(['action','customer'])->make('true');
        }
        if(!$request->has('trash')){
            return view('order.index');
        }
        return view('order.trashed');
    }

    public function actionButtons(Request $request, $dataTable){
        if(!$request->trash){
            $dataTable->addColumn('action',function($row){
                $button = '<a class="btn btn-info" style="min-width:100px;" href="' . route("orders.show", ["order" => $row]) . '"> Show </a>';
                if(!$row->done){
                    $button .= '<a class="btn btn-success" style="min-width:100px;margin-left:10px;" href="' . route("orders.edit", ["order" => $row]) . '"> Edit </a>';
                }
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                return $button;
            });
        }
        else{
            $dataTable->addColumn('action',function($row){
                $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("orders.restore", ["order" => $row->id ]) . '"> Restore </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')"> Final Delete</button>';
                return $button;
            });
        }

        return $dataTable;
    }

    public function create()
    {
        $customers = User::all();
        $products = Product::all();

        $basket = Basket::where('customer_id',Auth::id())->get();
        $images = [];
        $ids = array();
        $descriptions = array();

        BasketController::getBasketInfo($basket, $images, $ids, $descriptions);


        return view('order.create',compact('customers','products', 'images', 'ids', 'descriptions'));
    }

    public function getOptionVariant(Request $request)
    {
        $product = Product::find($request->id);
        $OptionVariantsList = $product->optionVariant()->get()->groupBy('variant_id');
        $OptionVariantsList = $this->getAllOptionVariant($OptionVariantsList,$product->id);

        return response()->json(['data'=> $OptionVariantsList,'success'=>'Added Successfully']);
    }

    public static function getAllOptionVariant($OptionVariantsList,$product)
    {
        $variants = $OptionVariantsList->keys();
        $list = "";
        foreach ($variants as $var){
            $variant = Variant::find($var);
            $list .= "<h3 id='$variant->id'>". $variant->name ."</h3>";
            $list .= "<select id='" . $variant->name . "' name='" . $variant->name . "' class='custom-select form-control' onchange='refreshTheQuantity()'>";
            $OptionVariants = $OptionVariantsList[$var];
            foreach ($OptionVariants as $OptionVariant){
                $option = $OptionVariant->option_id;
                $option = Option::find($option);
                $list .= "<option value=" . $option->name . ">" . $option->name . "</option>";
            }
            $list .= "</select>";

        }

        $list .= "<div style='text-align: center;margin-top: 10px;'><a class='btn btn-primary' name='basket' id='basket'  onclick='addToBasket(".$product.")'>Add To Basket</a></div>";

        return $list;
    }

    public static function store(Request $request)
    {
        $customer = $request->customer_id;

        $products = Basket::where('customer_id', $customer)->get();

        if(count($products) == 0){
            return redirect(route('orders.index'))->withErrors("Cannot Order An Empty Order");
        }

        $order = new Order();
        $order->customer_id = $customer;
        $order->save();

        foreach ($products as $detail){
            $orderVariantProducts = [];
            if($detail->order_variant_products != ''){
                $orderVariantProducts = explode(',', $detail->order_variant_products);
            }
            $product = $detail->product_id;
            $quantity = $detail->quantity;

            $detail->delete();

            $orderProduct = new OrderProduct();

            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $product;
            $orderProduct->amount = $quantity;
            $orderProduct->save();

            if($detail->order_variant_products != ''){
                foreach ($orderVariantProducts as $orderVariantProduct){
                    $orderProduct->optionVariantProducts()->attach($orderVariantProduct);
                }
            }
        }

        $order->notify(new OrderArrived());

        return redirect(route('orders.index'))->with('message','The Order was successfully Added.');
    }

    public function show(Order $order)
    {
        $products = array();
        $descriptions = array();
        $quantities = array();
        $customer = User::find($order->customer_id);
        $total = 0;

        $this->orderDetails($order, $products, $descriptions, $quantities, $customer, $total);

        return view('order.show', compact('order','descriptions', 'quantities', 'products', 'customer', 'total'));
    }

    public static function orderDetails(Order $order, &$products, &$descriptions, &$quantities, &$customer, &$total){
        $orderProducts = $order->orderProducts;
        foreach ($orderProducts as $orderProduct){
            array_push($products,$orderProduct->product);
            $description = $orderProduct->product->name . " With ";
            $optionVariantProducts = $orderProduct->optionVariantProducts;
            $total = count($optionVariantProducts);
            $count = 0;
            foreach ($optionVariantProducts as $optionVariantProduct) {
                $optionVariant = OptionVariant::find($optionVariantProduct->option_variant_id);
                $option = Option::find($optionVariant->option_id);
                $variant = Variant::find($optionVariant->variant_id);
                $description .= $variant->name . ": " . $option->name;
                $count++;
                if($count < $total){
                    $description .= ", ";
                }
            }
            array_push($descriptions,$description);
            array_push($quantities,$orderProduct->amount);
            $total += $orderProduct->product->price *  $orderProduct->amount;
        }
    }

    public function edit(Order $order)
    {
        $products = array();
        $descriptions = array();
        $quantities = array();
        $customer = User::find($order->customer_id);
        $total = 0;

        $this->orderDetails($order, $products, $descriptions, $quantities, $customer, $total);

        return view('order.edit', compact('order','descriptions', 'quantities', 'products', 'customer', 'total'));
    }

    public function update(Request $request, Order $order)
    {
        $order->done = $request->done;

        $order->save();

        return redirect(route('orders.index'))->with("message","The Record Was Updated Successfully");
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($order)
    {
        $order = Order::withTrashed()->where('id',$order)->first();
        $orderProducts = $order->orderProducts;

        foreach ($orderProducts as $orderProduct){
            $optionVariantProducts = $orderProduct->optionVariantProducts;
            foreach ($optionVariantProducts as $optionVariantProduct) {
                $orderProduct->optionVariantProducts()->detach($optionVariantProduct);
            }
            $order->orderProducts()->delete($orderProduct);
        }

        $order->forceDelete();

        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreOrder($order)
    {
        $order = Order::withTrashed()->where('id',$order)->first();
        $order->restore();
        return redirect()->back()->with("message","The Record Was Restored");
    }
}
