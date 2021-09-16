<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Image;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ImageTrait;

class CategoryController extends Controller
{
    use ImageTrait;

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
//        $categories = Category::where('depth',0)->with('children')->get();
//
//        return view('category.draft', compact('categories'));
        if ($request->ajax()) {
            $data = $request->trash ? Category::onlyTrashed() : Category::all();
            $dataTable = Datatables::of($data)
                ->addColumn('parent', function ($row) {
                    $parent = null;
                    if ($row->parent != null) {
                        $parent = $row->parent->name;
                    }
                    return $parent;
                })
                ->addColumn('products', function ($row) {
                    return count($row->products);
                })
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->ImagePath . '" border="0" width="150" class="img-rounded" alt="' . $row->image()->first()->saved_name . '" align="center" />';
                    return $image;
                });

            return $this->actionButtons($request,$dataTable)->rawColumns(['action', 'image', 'parent', 'products'])->make('true');
        }
        if (!$request->has('trash')) {
            return view('category.index');
        }
        return view('category.trashed');
    }

    public function actionButtons(Request $request,$dataTable){
        if (!$request->trash) {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-success" style="min-width:100px;" href="' . route("categories.edit", ["category" => $row]) . '"> Edit </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                return $button;
            });
        } else {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("categories.restore", ["category" => $row->id]) . '"> Restore </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')"> Final Delete</button>';
                return $button;
            });
        }
        return $dataTable;
    }

    public function create()
    {
        $categories = Category::all();
        return view('category.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = new Category();
        $category = $this->storeOrUpdateRecord($request, $category);
        $image = Category::uploadImage($request->file('image'), Category::$folderName, 1);
        $category->image()->save($image);

        return redirect(route('categories.index'))->with('message', 'The Category was Updated to Successfully. ');
    }

    public function storeOrUpdateRecord(Request $request, $cat)
    {
        $category = $cat;
        $category->name = $request->name;
        $category->description = $request->description;

        if ($request->parent != 0) {
            $category->parent_id = $request->parent;
            $parent = Category::find($category->parent_id);
            $category->depth = $parent->depth + 1;
        } else {
            $category->parent_id = null;
            $category->depth = 0;
        }

        $category->save();
        return $category;
    }

    public function show($id)
    {
        abort('404');
    }

    public function edit(Category $category)
    {
        $parent = $category->parent;
        $image = $category->imagePath;
        $categories = Category::where('id', '!=', $category->id)->get();

        return view('category.edit', compact('categories','image', 'category', 'parent'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            "name" => [
                'required',
                Rule::unique('categories')->ignore($category->name, 'name'),
                'max:255'
            ],
            "description" => "required",
            "image" => "image|mimes:jpeg,png,jpg",
        ]);

        $category = $this->storeOrUpdateRecord($request, $category);

        if ($request->has('image')) {
            Category::deleteImage($category->image->saved_name, Category::$folderName);
            $category->image()->delete();

            $image = Category::uploadImage($request->file('image'), Category::$folderName, 1);
            $category->image()->save($image);
        }

        return redirect(route('categories.index'))->with('message', 'The Category was Updated to Successfully. ');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($category)
    {
        $category = Category::withTrashed()->where('id', $category)->first();
        Category::deleteImage($category->image->saved_name, Category::$folderName);
        $category->image()->delete();
        $category->forceDelete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreCategory($category)
    {
        $category = Category::withTrashed()->where('id', $category)->first();
        $category->restore();
        return redirect()->back()->with("message", "The Record Was Restored");
    }

}
