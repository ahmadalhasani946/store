<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->trash ? User::onlyTrashed() : User::all();
            $dataTable = Datatables::of($data)
                ->addColumn('image', function ($row) {
                    $image = '<img src="' . $row->ImagePath . '" border="0" width="150" class="img-rounded" alt="' . $row->ImageAlt . '" align="center" />';
                    return $image;
                });

            return $this->actionButtons($request,$dataTable)->rawColumns(['action','image'])->make('true');
        }
        if (!$request->has('trash')) {
            return view('user.index');
        }
        return view('user.trashed');
    }

    public function actionButtons(Request $request,$dataTable){
        if (!$request->trash) {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-success" style="min-width:100px;" href="' . route("users.edit", ["user" => $row]) . '"> Edit </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="deleteRecord(' . $row->id . ')">Delete</button>';
                return $button;
            });
        } else {
            $dataTable->addColumn('action', function ($row) {
                $button = '<a class="btn btn-success" style="min-width:100px;"  href="' . route("users.restore", ["user" => $row->id]) . '"> Restore </a>';
                $button .= '<button type="submit" style="min-width:100px;margin-left:10px;" class="btn btn-danger" data-toggle="modal" data-target="#DeleteModal" onclick="forceDelete(' . $row->id . ')"> Final Delete</button>';
                return $button;
            });
        }
        return $dataTable;
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = new User();
        $user = $this->storeOrAddUser($request,$user);
        if($request->has('image')){
            $image = User::uploadImage($request->file('image'), User::$folderName, 1);
            $user->image()->save($image);
        }

        return redirect(route('users.index'))->with('message','The Record Was Added Successfully');
    }

    public function storeOrAddUser(Request $request,$user){
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password != null){
            $user->password = Hash::make($request->password);
        }
        $user->phone = $request->phone;
        $user->address = $request->address;
        if($request->has('is_admin')){
            $user->is_admin = $request->is_admin;
        }
        $user->save();

        return $user;
    }

    public function show(User $user)
    {

    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => [
                'required',
                Rule::unique('users')->ignore($user->name, 'name'),
                'max:255',
                'string'
            ],
            "email" => [
                'required',
                Rule::unique('users')->ignore($user->email, 'email'),
                'max:255',
                'string',
                'email'
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|regex:/^0([0-9\s\-\+\(\)]{10})$/',
            'address' => 'nullable|max:255',
            "image" => "image|mimes:jpeg,png,jpg",
        ]);

        $user = $this->storeOrAddUser($request,$user);
        if($request->has('image')){
            if($user->image){
                User::deleteImage($user->image->saved_name, User::$folderName);
                $user->image()->delete();
            }
            $image = User::uploadImage($request->file('image'), User::$folderName, 1);
            $user->image()->save($image);
        }

        if(str_contains(url()->previous(),'profile')){
            return redirect(route('profile'))->with('message','The Profile Was Updated Successfully');
        }
        return redirect(route('users.index'))->with('message','The Record Was Updated Successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function forceDelete($user)
    {
        $user = User::withTrashed()->where('id', $user)->first();

        $user->reviews()->forceDelete();

        if($user->image){
            User::deleteImage($user->image->saved_name, User::$folderName);
            $user->image()->delete();
        }

        $orders = $user->orders;

        foreach ($orders as $order){
            $orderProducts = $order->orderProducts;
            foreach ($orderProducts as $orderProduct){
                $optionVariantProducts = $orderProduct->optionVariantProducts;
                foreach ($optionVariantProducts as $optionVariantProduct) {
                    $orderProduct->optionVariantProducts()->detach($optionVariantProduct);
                }
                $orderProduct->delete();
            }
            $order->forceDelete();
        }

        $user->forceDelete();
        return redirect()->back()->withErrors("The Record Was Deleted");
    }

    public function restoreUser($user)
    {
        $user = User::withTrashed()->where('id', $user)->first();
        $user->restore();
        return redirect()->back()->with("message","The Record Was Restored");
    }
}
