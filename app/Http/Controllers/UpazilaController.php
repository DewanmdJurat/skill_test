<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upazila;
use Auth;

class UpazilaController extends Controller
{
    public function index()
    {
        return view('priv.upazila.index');
    }

    public function upazilas()
    {
        $page   = request('page')-1;
        $offset = request('offset');
        $search = request('search') != '' ? request('search') : '';
        $user_id            = Auth::user()->id;
        $upazilas          = Upazila::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('upazila_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->skip($page * $offset)
                                        ->take($offset)
                                        ->get();

        $upazila_count     = Upazila::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('upazila_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->get()
                                        ->count();
        return response()->json([
            'upazilas' => $upazilas,
            'upazila_count' => $upazila_count
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'upazila_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $upazila                 = new Upazila;
        $upazila->upazila_name  = $request->upazila_name;
        $upazila->created_by     = $user_id;
        if($upazila->save())
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
        $upazila     = Upazila::where('created_by',$user_id)
                                ->where('id', $id)
                                ->where('status', 1)
                                ->first();
        if($upazila)
        {
            $notification = array();
            return response()->json([
                'upazila' => $upazila,
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
            'upazila_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $upazila                 = Upazila::where('created_by',$user_id)->where('id', $request->upazila_id)->first();
        $upazila->upazila_name  = $request->upazila_name;
        $upazila->updated_by     = $user_id;
        if($upazila->save())
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
        $user_id   = Auth::user()->id;
        $upazila  = Upazila::where('created_by', $user_id)->where('id', $id)->first();
        if($upazila->delete())
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
