<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use Auth;

class DistrictController extends Controller
{
    public function index()
    {
        return view('priv.district.index');
    }

    public function districts()
    {
        $page   = request('page')-1;
        $offset = request('offset');
        $search = request('search') != '' ? request('search') : '';
        $user_id            = Auth::user()->id;
        $districts          = District::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('district_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->skip($page * $offset)
                                        ->take($offset)
                                        ->get();

        $district_count     = District::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('district_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->get()
                                        ->count();
        return response()->json([
            'districts' => $districts,
            'district_count' => $district_count
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'district_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $district                 = new District;
        $district->district_name  = $request->district_name;
        $district->created_by     = $user_id;
        if($district->save())
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
        $district     = District::where('created_by',$user_id)
                                ->where('id', $id)
                                ->where('status', 1)
                                ->first();
        if($district)
        {
            $notification = array();
            return response()->json([
                'district' => $district,
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
            'district_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $district                 = District::where('created_by',$user_id)->where('id', $request->district_id)->first();
        $district->district_name  = $request->district_name;
        $district->updated_by     = $user_id;
        if($district->save())
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
        $district  = District::where('created_by', $user_id)->where('id', $id)->first();
        if($district->delete())
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
