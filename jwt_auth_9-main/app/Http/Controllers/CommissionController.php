<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $commision = Commission::all();
        $count = DB::table('commissions')->count();

        return response()->json(['data' => $commision, 'count' => $count], 200);
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
            'percentage' => 'required',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            // $hairstyle = Commission::create([
            //     'name' => $request->name,
            //     'percentage' => $request->percentage,
            // ]);
            $commision = new Commission;
            $commision->name = $request->name;
            $commision->percentage = $request->percentage;
            $commision->save();
            if ($request->status) {
                $commision->status = $request->status;
                $commision->save();
            }

            return response()->json(['message' => 'Commission save Successfully', 'data' => $commision], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $commision = Commission::find($id);
        if ($commision) {
            $commision = Commission::find($id);

            return response()->json(['result' => $commision], 200);
        } else {
            return response([
                'message' => 'Commission  Data not found',
                'status' => '404',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $commision = Commission::find($id);
        if ($commision) {
            Commission::where('id', $id)
                ->update([
                    'name' => $request->name,
                    'percentage' => $request->percentage,
                    'status' => $request->status,
                ]);
            $commision = Commission::find($id);

            return response([
                'data' => $commision,
                'message' => 'commision edit Successfully',
                'status' => 'success',
            ], 201);
        } else {
            return response([
                'message' => 'commision data not found',
                'status' => '404',
            ], 404);
        }
    }

    public function updatestatus(Request $request, $id)
    {
        //    Commission::where('id', $id)
        //             ->update([
        //                 'status' => $request->status,
        //             ]);

        $commision = Commission::find($id);
        if ($commision) {
            $records = Commission::where('status', 'active')->get();
            $count = $records->count();

            for ($i = 0; $i < $count; $i++) {
                $record = $records[$i];
                $record->status = 'inactive';
                $record->save();
            }
            $commision = Commission::find($id);
            $commision->status = $request->status;
            $commision->save();

            return response()->json([
                'message' => 'Status updated successfully', 'data' => $commision,
            ], 200);
        } else {
            return response([
                'message' => 'Commission  Data not found',
                'status' => '404',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Commission  $commission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commision = Commission::find($id);
        if ($commision) {
            Commission::destroy($id);

            return response()->json(['message' => 'commision delete Successfully', 'data' => $commision], 200);
        } else {
            return response([
                'message' => 'commision not found',
                'status' => '404',
            ], 404);
        }
    }
}
