<?php

namespace App\Http\Controllers;

use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time = Time::all();
        $count = DB::table('times')->count();

        return response()->json([
            'result' => $time, 'count' => $count,
        ], 200);
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
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $user = auth()->guard('api')->user();
            $time = Time::create([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'created_by' => $user->id,
            ]);
            $timecount = Time::all();

            return response()->json(['message' => 'Time save Successfully', 'data' => $time], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $time = Time::find($id);

        if ($time) {
            $time = Time::find($id);

            return response()->json(['result' => $time], 200);
        } else {
            return response([
                'message' => 'Time Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Time $time)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $time = Time::find($id);
        if ($time) {
            $user = auth()->guard('api')->user();
            $time = Time::where('id', $id)
                ->update([
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'created_by' => $user->id,
                ]);
            $time = Time::find($id);

            return response([
                'message' => 'Time edit Successfully',
                'status' => 'success',
                'data' => $time,
            ], 201);
        } else {
            return response([
                'message' => 'Time Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $time = Time::find($id);
        if ($time) {
            Time::destroy($id);

            return response()->json(['message' => 'time delete Successfully', 'data' => $time], 200);
        } else {
            return response([
                'message' => 'time not found',
                'status' => '404',
            ], 404);
        }
    }
}
