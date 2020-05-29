<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Union;
use Auth;

class UnionController extends Controller
{
    public function index()
    {
        return view('priv.union.index');
    }

    public function unions()
    {
        $page   = request('page')-1;
        $offset = request('offset');
        $search = request('search') != '' ? request('search') : '';
        $user_id         = Auth::user()->id;
        $unions          = Union::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('union_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->skip($page * $offset)
                                        ->take($offset)
                                        ->get();

        $union_count     = Union::where('created_by',$user_id)
                                        ->when($search != '', function ($query) use ($search) {
                                            return $query->where('union_name','like','%'. $search. '%');
                                        })
                                        ->where('status', 1)
                                        ->get()
                                        ->count();
        return response()->json([
            'unions' => $unions,
            'union_count' => $union_count
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'union_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $union                 = new Union;
        $union->union_name  = $request->union_name;
        $union->created_by     = $user_id;
        if($union->save())
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
        $union     = Union::where('created_by',$user_id)
                                ->where('id', $id)
                                ->where('status', 1)
                                ->first();
        if($union)
        {
            $notification = array();
            return response()->json([
                'union' => $union,
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
            'union_name' => 'required | string | max:255',
        ]);
        $user_id  = Auth::user()->id;

        $union                 = Union::where('created_by',$user_id)->where('id', $request->union_id)->first();
        $union->union_name  = $request->union_name;
        $union->updated_by     = $user_id;
        if($union->save())
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
        $union  = Union::where('created_by', $user_id)->where('id', $id)->first();
        if($union->delete())
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
