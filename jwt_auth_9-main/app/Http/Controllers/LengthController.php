<?php

namespace App\Http\Controllers;

use App\Models\Length;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LengthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $length = Length::all();
        $count = DB::table('lengths')->count();

        return response()->json(['result' => $length, 'count' => $count], 200);
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
            'number' => ['required', 'regex:/^Number [1-8]$/'],
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $length = Length::create([
                'number' => $request->number,
            ]);

            return response()->json(['message' => 'Length save Successfully', 'data' => $length], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Length  $length
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $length = Length::find($id);
        if ($length) {
            $length = Length::find($id);

            return response()->json(['result' => $length], 200);
        } else {
            return response()->json(['result' => 'Length not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Length $length)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Length  $length
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validate inputs
        $validator = Validator::make($request->all(), [
            'number' => ['required', 'regex:/^Number [1-8]$/'],
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $length = Length::find($id);
            if ($length) {
                Length::where('id', $id)
                    ->update([
                        'number' => $request->number,
                    ]);
                $length = Length::find($id);

                return response([
                    'data' => $length,
                    'message' => 'Length edit Successfully',
                    'status' => 'success',
                ], 201);
            } else {
                return response([
                    'message' => 'Length not found',
                    'status' => 'failed',
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Length  $length
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $length = Length::find($id);
        if ($length) {
            Length::destroy($id);

            return response()->json(['message' => 'Length delete Successfully', 'data' => $length], 200);
        } else {
            return response([
                'message' => 'Length not found',
                'status' => 'failed',
            ], 404);
        }
    }
}
