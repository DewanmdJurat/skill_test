<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use Auth;

class DivisonController extends Controller
{
    public function index()
    {
        return view('priv.divisions.index');
    }

    public function divisions()
    {
        $page   = request('page')-1;
        $offset = request('offset');
        $search = request('search') != '' ? request('search') : '';
        $user_id            = Auth::user()->id;
        $divisions          = Division::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('division_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->skip($page * $offset)
                                        ->take($offset)
                                        ->get();

        $division_count     = Division::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('division_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->get()
                                        ->count();
        return response()->json([
            'divisions' => $divisions,
            'division_count' => $division_count
        ]);
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $division                 = new Division;
        $division->division_name  = $request->division_name;
        $division->created_by     = $user_id;
        if($division->save())
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
        $division     = Division::where('created_by',$user_id)
                                ->where('id', $id)
                                ->where('status', 1)
                                ->first();
        if($division)
        {
            $notification = array();
            return response()->json([
                'division' => $division,
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
            'division_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $division                 = Division::where('created_by',$user_id)->where('id', $request->division_id)->first();
        $division->division_name  = $request->division_name;
        $division->updated_by     = $user_id;
        if($division->save())
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
        $division  = Division::where('created_by', $user_id)->where('id', $id)->first();
        if($division->delete())
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
