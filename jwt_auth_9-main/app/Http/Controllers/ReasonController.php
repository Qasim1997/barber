<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reason = Reason::all();
        $count = DB::table('reasons')->count();

        return response()->json(['data' => $reason, 'count' => $count], 200);
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
            'reason' => 'required',
            'deduction' => 'required',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $reason = Reason::create([
                'reason' => $request->reason,
                'deduction' => $request->deduction,

            ]);

            return response()->json(['message' => 'Reason save Successfully', 'data' => $reason], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reason  $reason
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reason = Reason::find($id);
        if ($reason) {
            $reason = Reason::find($id);

            return response()->json(['result' => $reason], 200);
        } else {
            return response([
                'message' => 'Reason not found',
                'status' => '404',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Reason $reason)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Reason  $reason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reason = Reason::find($id);
        if ($reason) {
            Reason::where('id', $id)
            ->update([
                'reason' => $request->reason,
                'deduction' => $request->deduction,
            ]);
            $reason = Reason::find($id);

            return response([
                'data' => $reason,
                'message' => 'Reason edit Successfully',
                'status' => 'success',
            ], 201);
        } else {
            return response([
                'message' => 'Reason not found',
                'status' => '404',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reason  $reason
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reason = Reason::find($id);
        if ($reason) {
            Reason::destroy($id);

            return response()->json(['message' => 'Reason delete Successfully', 'data' => $reason], 200);
        } else {
            return response([
                'message' => 'reason not found',
                'status' => '404',
            ], 404);
        }
    }
}
