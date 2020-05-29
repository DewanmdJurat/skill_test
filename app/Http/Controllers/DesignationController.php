<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;
use Auth;

class DesignationController extends Controller
{
    public function index()
    {
        return view('priv.designation.index');
    }

    public function designations()
    {
        $page   = request('page')-1;
        $offset = request('offset');
        $search = request('search') != '' ? request('search') : '';
        $user_id         = Auth::user()->id;
        $designations          = Designation::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('designation_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->skip($page * $offset)
                                        ->take($offset)
                                        ->get();

        $designation_count     = Designation::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('designation_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->get()
                                        ->count();
        return response()->json([
            'designations' => $designations,
            'designation_count' => $designation_count
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $designation                 = new Designation;
        $designation->designation_name  = $request->designation_name;
        $designation->created_by     = $user_id;
        if($designation->save())
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
        $designation     = Designation::where('created_by',$user_id)
                                ->where('id', $id)
                                ->where('status', 1)
                                ->first();
        if($designation)
        {
            $notification = array();
            return response()->json([
                'designation' => $designation,
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
            'designation_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $designation                 = Designation::where('created_by',$user_id)->where('id', $request->designation_id)->first();
        $designation->designation_name  = $request->designation_name;
        $designation->updated_by     = $user_id;
        if($designation->save())
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
        $designation  = Designation::where('created_by', $user_id)->where('id', $id)->first();
        if($designation->delete())
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
