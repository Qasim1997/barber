<?php

namespace App\Http\Controllers;

use App\Models\Hairstyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HairstyleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('api')->user();
        // $posts = Hairstyle::with('user')->get();

        if ($user->user_type === 'barber') {
            // $hairstyle = Hairstyle::order('created_by', 'asc')->get();
            $hairstyle = Hairstyle::orderBy('created_by', 'asc')->get();
            $count = Hairstyle::orderBy('created_by', 'asc')->count();

        // $count = count($hairstyle);
        } else {
            $hairstyle = Hairstyle::where('type', 'admin')->get();
            $count = Hairstyle::where('type', 'admin')->count();

            // $count = count($hairstyle);
        }
        // $hairstyle = Hairstyle::orderBy('created_by', 'asc')->get();

        // else {
        //     $hairstyle = Hairstyle::where('type', 'admin')->get();
        // }
        // $hairstyle = Hairstyle::all();
        // $count = DB::table('hairstyles')->count();
        return response()->json(['data' => $hairstyle, 'count' => $count]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $filename = null;
            if ($request->file('image')) {
                // $filename = $request->file('image')->hashName();
                // $image_path = 'admin_assets/uploads/hairstyle/';
                // $request->image->move(public_path($image_path), $filename);
                $filename = time().'.'.$request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/hairstyle', $filename);
            }
            // return $path;
            $user = auth()->guard('api')->user();
            $hairstyle = Hairstyle::create([
                'name' => $request->name,
                'image' => $filename,
                'created_by' => $user->id,
                'type' => $user->user_type,
            ]);

            return response()->json(['message' => 'Hairstyle save Successfully', 'data' => $hairstyle, $path, $filename], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hairstyle  $hairstyle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hairstyle = Hairstyle::find($id);

        if ($hairstyle) {
            $hairstyle = Hairstyle::find($id);

            return response()->json(['result' => $hairstyle], 200);
        } else {
            return response([
                'message' => 'Hairstyle Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Hairstyle $hairstyle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Hairstyle  $hairstyle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hairstyle = Hairstyle::find($id);
        if ($hairstyle) {
            $filename = null;
            // if ($request->file('image')) {
            //     $filename = $request->file('image')->hashName();
            //     $image_path = 'admin_assets/uploads/';
            //     $request->image->move(public_path($image_path), $filename);
            // }
            $user = auth()->guard('api')->user();
            $hairstyle = Hairstyle::find($id);
            $hairstyle->name = $request->name;
            $hairstyle->save();
            if ($request->file('image')) {
                $exists = Storage::disk('public')->exists("hairstyle/{$hairstyle->image}");
                if ($exists) {
                    Storage::disk('public')->delete("hairstyle/{$hairstyle->image}");
                }
                $filename = time().'.'.$request->file('image')->getClientOriginalExtension();
                $path = $request->file('image')->storeAs('public/hairstyle', $filename);
                $hairstyle->image = $filename;
                $hairstyle->save();
            }
            $hairstyle = Hairstyle::find($id);

            return response([
                'message' => 'Hairstyle edit Successfully',
                'status' => 'success',
                'data' => $hairstyle,
                'exist' => $exists,
            ], 201);
        } else {
            return response([
                'message' => 'Hairstyle Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hairstyle  $hairstyle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hairstyle = Hairstyle::find($id);

        if ($hairstyle) {
            $flight = Hairstyle::where('id', $id)->first();
            // Storage::delete('public/admin_assets/uploads/hairstyle/Xfvc7hhAm3SaqzSJsbxkj95smGBJo9Mt1K3sttun.jpg');
            $exists = Storage::disk('public')->exists("hairstyle/{$flight->image}");
            if ($exists) {
                Storage::disk('public')->delete("hairstyle/{$flight->image}");
            }
            // Storage::disk('public')->delete("hairstyle/{$flight->image}");
            Hairstyle::destroy($id);

            return response()->json(['message' => 'Hairstyle delete Successfully', 'data' => $hairstyle, 'flight' => $flight, $exists], 200);
        } else {
            return response([
                'message' => 'Hairstyle Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }
}
