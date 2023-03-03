<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $day = Day::all();
        $count = DB::table('days')->count();

        return response()->json(['result' => $day, 'count' => $count], 200);
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
            'time_id' => 'required|array',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $myArray = $request->time_id;
            $count = count($myArray);
            // for ($i = 0; $i < $count; $i++) {
            //     $ui = $i;
            //     $time = Time::where('id', $myArray[$i])->get();

            // }
            $count = --$count;
            $formattedData = [];

            // for ($i = 1; $i <= 2; $i++) {
            //     // Query the database
            //     // $queryResult = DB::table('table_' . $i)->get();
            //     $queryResult = Time::where('id', $myArray[$i])->get();

            //     // Format the data
            //     $formattedData[] = [
            //         // 'table' => 'table_' . $i,
            //         'data' => $queryResult,
            //     ];
            // }
            $x = 0;
            do {
                $queryResult = Time::where('id', $myArray[$x])->get();
                // Format the data
                $formattedData[] = [
                    // 'table' => 'table_' . $i,
                    'data' => $queryResult,
                ];
                $x++;
            } while ($x <= $count);

            $i = 0;
            do {
                $day = Day::create([
                    'day' => $request->day,
                    'time_id' => $myArray[$i],
                ]);
                $i++;
            } while ($i <= $count);
            // $myString is now 'apple,banana,orange'
            $counts = count($myArray);
        }

        return response()->json($formattedData);

        // $time = Time::where('id', $request->time_id)->get();
        // return response()->json([
        //     'message' => 'Day Save Successfully',
        //     'data' => $time,
        //     $ui ,
        //     'time' => $myArray[0],
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $time = Day::find($id);

        if ($time) {
            $time = Day::find($id);

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
    public function edit(Day $day)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $time = Day::find($id);
        if ($time) {
            $user = auth()->guard('api')->user();
            $time = Day::where('id', $id)
                ->update([
                    'day' => $request->day,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'time_id' => $request->time_id,
                ]);
            $time = Day::find($id);

            return response([
                'message' => 'Day edit Successfully',
                'status' => 'success',
                'data' => $time,
            ], 201);
        } else {
            return response([
                'message' => 'Day Data Not Found',
                'status' => 'failed',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Day  $day
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $day = Day::find($id);
        if ($day) {
            Day::destroy($id);

            return response()->json(['message' => 'day delete Successfully', 'data' => $day], 200);
        } else {
            return response([
                'message' => 'day not found',
                'status' => '404',
            ], 404);
        }
    }
}
