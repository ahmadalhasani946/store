<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Models\OptionVariantProduct;
use Illuminate\Http\Request;
use App\Models\Option;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->trash ? Option::onlyTrashed() : Option::all();
            $dataTable = Datatables::of($data);
            if(!$request->trash){
                $dataTable->addColumn('action', function ($row) {
                    $button = '<a class="btn btn-success" style="min-width:100px;" href="' . route("options.edit", ["option" => $row]) . '"> Edit </a>';
                    $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                    return $button;
                });
            }
            else{
                $dataTable->addColumn('action', function ($row) {
                    $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("options.restore", ["option" => $row->id]) . '"> Restore </a>';
                    $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')"> Final Delete</button>';
                    return $button;
                });
            }
            return $dataTable->rawColumns(['action'])->make('true');
        }
        if(!$request->has('trash')){
            return view('option.index');
        }
        return view('option.trashed');
    }


    public function create()
    {
        return view('option.create');
    }

    public function store(StoreOptionRequest $request)
    {
        $validated = $request->validated();
        $option = new Option();
        $option->name = $request->name;
        $option->save();
        return redirect(route('options.index'))->with('message', 'The Option Named As ' . $option->name . ' was successfully Added.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Option $option)
    {
        return view('option.edit', compact('option'));
    }

    public function update(Request $request,Option $option)
    {
        $validated = $request->validate([
            "name" => [
                'required',
                Rule::unique('options')->ignore($option->name, 'name'),
                'max:255'
            ],
        ]);

        $option->name = $request->name;
        $option->save();

        return redirect(route('options.index'))->with('message', 'The Option was Updated to Successfully.');
    }

    public function destroy(Request $request,Option $option)
    {
        $option->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($option)
    {
        $option = Option::withTrashed()->where('id', $option)->first();

        $variants = $option->variants()->get();


        $variants = $option->variants()->get();
        foreach ($variants as $variant){
            OptionVariantProduct::select()->where('option_variant_id', $variant->pivot->id)->delete();
            $option->variants()->detach($variant);
        }
        $option->forceDelete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreOption($option)
    {
        $option = Option::withTrashed()->where('id',$option)->first();
        $option->restore();
        return redirect()->back()->with("message","The Record Was Restored");
    }

}
