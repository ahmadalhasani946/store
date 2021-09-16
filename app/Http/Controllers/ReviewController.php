<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->trash ? Review::onlyTrashed() : Review::all();
            $dataTable = Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('customer',function($row){
                    $customer = User::withTrashed()->where('id',$row->customer_id)->first();
                    return $customer->name;
                })
                ->addColumn('product',function($row){
                    $product = Product::withTrashed()->where('id',$row->product_id)->get();
                    return $product[0]->name;
                })
                ->addColumn('stars',function($row){
                    $stars = "<div class='starrating'>";
                    for($i = 0; $i < $row->stars; $i++){
                        $stars .= "<label style='color:gold;' title='star'></label>";
                    }
                    $stars .= "<div>";
                    return $stars;
                });

            return $this->actionButtons($request,$dataTable) ->rawColumns(['action','customer','product','stars'])->make('true');
        }
        if (!$request->has('trash')) {
            return view('review.index');
        }
        return view('review.trashed');
    }

    public function actionButtons(Request $request, $dataTable){
        if (!$request->trash) {
            $dataTable->addColumn('action',function($row){
                $button = '<a class="btn btn-success" style="min-width:100px;margin-left:10px;" href="' . route("reviews.edit", ["review" => $row ]) . '"> Edit </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                return $button;
            });
        }
        else {
            $dataTable->addColumn('action',function($row){
                $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("reviews.restore", ["review" => $row->id]) . '"> Restore </a>';
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
        if(!$products){
            return redirect(route('products.index'))->withErrors('You Have To Add Product First To Be Able To Create A Review');
        }
        return view('review.create',compact('customers','products'));
    }

    public function store(StoreReviewRequest $request)
    {
        $validated  = $request->validated();
        $review = new Review();
        $this->storeOrUpdateReview($request, $review);

        if(str_contains(url()->previous(),'product')){
            return redirect(url()->previous())->with('message','The Review was successfully Added');
        }

        return redirect(route('reviews.index'))->with('message','The Review was successfully Added.');
    }

    public function show($id)
    {
    }

    public function edit(Review $review)
    {
        $customers = User::all();
        $products = Product::all();

        if(str_contains(url()->previous(),'product')){
            return response()->json(['review' => $review]);
        }

        return view('review.edit',compact('customers','products','review'));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $validated  = $request->validated();
        $this->storeOrUpdateReview($request, $review);

        if(str_contains(url()->previous(),'product')){
            return back()->with('message','The Review Was Updated Successfully');
        }

        return redirect(route('reviews.index'))->with('message','The Review was successfully Updated.');
    }

    public function storeOrUpdateReview(Request $request, Review $review){
        $review->comment = $request->comment;
        $review->customer_id = $request->customer_id;
        $review->product_id = $request->product_id;
        $review->stars = $request->stars;
        if($request->has('to_show')){
            $review->to_show = $request->to_show;
        }
        else{
            $review->to_show = 1;
        }

        $review->save();
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back();
    }

    public function restoreReview($review)
    {
        $review = Review::withTrashed()->where('id', $review)->first();
        $review->restore();
        return redirect()->back();
    }

    public function forceDelete($review)
    {
        $review = Review::withTrashed()->where('id',$review)->first();
        $review->forceDelete();
        return redirect()->back();
    }

}
