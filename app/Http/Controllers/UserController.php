<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\User;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $user_id        = Auth::user()->id;
        $designations  = Designation::where('created_by',$user_id)->where('status', 1)->get();
        $divisions     = Division::where('created_by',$user_id)->where('status', 1)->get();
        $districts     = District::where('created_by',$user_id)->where('status', 1)->get();
        $upazilas      = Upazila::where('created_by',$user_id)->where('status', 1)->get();
        $unions        = Union::where('created_by',$user_id)->where('status', 1)->get();

        return view('priv.user.index', compact('designations', 'divisions', 'districts', 'upazilas', 'unions'));
    }

    public function users()
    {
        $page           = request('page')-1;
        $offset         = request('offset');
        $search         = request('search') != '' ? request('search') : '';
        $user_id        = Auth::user()->id;
        $user_lavel     = User::where('id', $user_id)->first()->user_lavel;

        $users          = User::when($search != '', function ($query) use ($search) {
                                    return $query->where('user_name','like','%'. $search. '%');
                                })
                                ->when($user_lavel == 'district', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division');
                                })
                                ->when($user_lavel == 'upazila', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division')->where('user_lavel', '!=', 'district');
                                })
                                ->skip($page * $offset)
                                ->take($offset)
                                ->get()->load('designation','division', 'district', 'upazila', 'union');

        $user_count     = User::when($search != '', function ($query) use ($search) {
                                    return $query->where('user_name','like','%'. $search. '%');
                                })
                                ->when($user_lavel == 'district', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division');
                                })
                                ->when($user_lavel == 'upazila', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division')->where('user_lavel', '!=', 'district');
                                })
                                ->get()
                                ->count();
        return response()->json([
            'users' => $users,
            'user_count' => $user_count
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required | string | max:255',
            'email'         => 'required | string | max:190 | unique:users',
            'password'      => 'required | string | max:255 | min:8',
            'designation'   => 'required | numeric | max:20',
            'division'      => 'nullable | numeric | max:20',
            'district'      => 'nullable | numeric | max:20',
            'upazila'       => 'nullable | numeric | max:20',
            'union'         => 'nullable | numeric | max:20',
            'user_lavel'    => 'required | string | max:20',
        ]);
        $user_id  = Auth::user()->id;

        $user                 = new User;
        $user->name           = $request->name;
        $user->email          = $request->email;
        $user->password       = Hash::make($request['password']);
        $user->designation_id = $request->designation;
        $user->division_id    = $request->division;
        $user->district_id    = $request->district;
        $user->upazila_id     = $request->upazila;
        $user->union_id       = $request->union;
        $user->user_lavel     = $request->user_lavel;
        if($user->save())
        {
            $notification = array('message' => 'Information Created Successfully', 'alert-type'=> 'success');
        }
        else 
        {
            $notification = array('message' => 'Something went wrong!', 'alert-type'=> 'error');
        }

        return response()->json($notification);
    }

    public function edit($id)
    {
        $user_id      = Auth::user()->id;

        $user_lavel   = User::where('id',$user_id)
                                ->first()->user_lavel;

        $user         = User::where('id',$id)
                                ->when($user_lavel == 'district', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division');
                                })
                                ->when($user_lavel == 'upazila', function ($query) use ($user_lavel) {
                                    return $query->where('user_lavel', '!=' ,'division')->where('user_lavel', '!=', 'district');
                                })
                                ->first()->load('designation','division', 'district', 'upazila', 'union');
        if($user)
        {
            $notification = array();
            return response()->json([
                'user' => $user,
                'notification' => $notification,
            ]);
        }
        else 
        {
            $notification = array('message' => 'Access denied', 'alert-type'=> 'error');
            return response()->json($notification);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'required | string | max:255',
            'email'         => 'required | string | max:190 | unique:users,email,'.$request->user_id,
            'password'      => 'nullable | string | max:255 | min:8',
            'designation'   => 'required | numeric | max:20',
            'division'      => 'nullable | numeric | max:20',
            'district'      => 'nullable | numeric | max:20',
            'upazila'       => 'nullable | numeric | max:20',
            'union'         => 'nullable | numeric | max:20',
            'user_lavel'    => 'required | string | max:20',
        ]);

        $user                 = User::where('id', $request->user_id)->first();
        $user->name           = $request->name;
        $user->email          = $request->email;
        if($request->password) {
            $user->password       = Hash::make($request['password']);
        }
        $user->designation_id = $request->designation;
        $user->division_id    = $request->division;
        $user->district_id    = $request->district;
        $user->upazila_id     = $request->upazila;
        $user->union_id       = $request->union;
        $user->user_lavel     = $request->user_lavel;

        if($user->save())
        {
            $notification = array('message' => 'Information Updated Successfully', 'alert-type'=> 'success');
        }
        else 
        {
            $notification = array('message' => 'Something went wrong!', 'alert-type'=> 'error');
        }

        return response()->json($notification);
    }

    public function delete($id)
    {
        $user      = User::where('id', $id)->first();
        if($user->delete())
        {
            $notification = array('message' => 'Information Deleted Successfully', 'alert-type'=> 'success');
        }
        else 
        {
            $notification = array('message' => 'Something went wrong!', 'alert-type'=> 'error');
        }
        return response()->json($notification);
    }
}
