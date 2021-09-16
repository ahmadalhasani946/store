<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVariantRequest;
use App\Http\Requests\UpdateVariantRequest;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\Option;
use Yajra\DataTables\Facades\DataTables;
use App\Models\OptionVariant;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class VariantController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);

    }

    public function index(Request  $request)
    {
        if ($request->ajax()) {
            $data = $request->trash ? Variant::onlyTrashed() : Variant::all();
            $dataTable = Datatables::of($data);
            if(!$request->trash){
                $dataTable->addColumn('action',function($row){
                    $button = '<a class="btn btn-info" style="min-width:100px;margin-left:10px;" href="' . route("variants.show", ["variant" => $row ]) . '"> Show </a>';
                    $button .= '<a class="btn btn-success" style="min-width:100px;margin-left:10px;" href="' . route("variants.edit", ["variant" => $row ]) . '"> Edit </a>';
                    $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                    return $button;
                });
            }
            else{
                $dataTable->addColumn('action',function($row){
                    $button = '<a class="btn btn-success" style="min-width:100px;margin-left:10px;" href="' . route("variants.restore", ["variant" => $row ]) . '"> Restore </a>';
                    $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')">Final Delete</button>';
                    return $button;
                });
            }
            return $dataTable->rawColumns(['action'])->make('true');
        }
        if(!$request->has('trash')){
            return view('Variant.index');
        }
        return view('Variant.trashed');
    }


    public function create()
    {
        $options = Option::all();
        $optionCount = $options->count();
        if($optionCount == 0){
            return redirect(route('options.index'))->withErrors("You Have To Add Options First To Be Able To Add Variants");
        }
        return view('variant.create', compact('options'));
    }

    public function store(StoreVariantRequest $request)
    {
        $validated = $request->validated();
        $variant = new Variant();
        $variant->name = $request->name;
        $variant->save();
        foreach($request->options as $option){
            $variant->options()->attach($option);
        }
        return redirect(route('variants.index'))->with('message','The Variant Named As ' . $variant->name . ' was successfully Added.');
    }

    public function show(Variant $variant)
    {
        $options = $variant->options()->get();
        return view('variant.show', compact('options','variant'));
    }

    public function edit(Variant $variant)
    {
        $variantOptions = $variant->options()->get();
        $varOpt = array();
        foreach($variantOptions as $variantOption){
            array_push($varOpt,$variantOption->id);
        }
        $variantOptions = $varOpt;

        $options = Option::all();
        return view('variant.edit', compact('options','variantOptions', 'variant'));
    }

    public function update(Request $request, Variant $variant)
    {
        $validated = $request->validate([
            "name" => [
                'required',
                Rule::unique('variants')->ignore($variant->name, 'name'),
                'max:255'
            ],
            "options" => 'required'
        ]);

        $variantOptions = $variant->options()->get();
        foreach($variantOptions as $variantOption){
            $variant->options()->detach($variantOption);
        }

        $variant->name = $request->name;
        $variant->save();

        foreach($request->options as $option){
            $variant->options()->attach($option);
        }

        return redirect(route('variants.index'))->with('message','The Variant Named As ' . $variant->name . ' was successfully Updated.');
    }

    public function destroy(Variant $variant)
    {
        $variant->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($variant)
    {
        $variant = Variant::withTrashed()->where('id',$variant)->first();
        $variantOptions = $variant->options()->get();
        foreach($variantOptions as $variantOption){
            $variant->options()->detach($variantOption);
        }
        $variant->forceDelete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreVariant($variant)
    {
        $variant = Variant::withTrashed()->where('id',$variant)->first();
        $variant->restore();
        return redirect()->back()->with("message","The Record Was Restored");
    }
}
