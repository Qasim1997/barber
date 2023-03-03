<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $price = Price::all();
        $count = DB::table('prices')->count();

        return response()->json(['data' => $price, 'count' => $count], 200);
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
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response([$validator->messages()], 400);
        } else {
            $data = Price::updateOrCreate(
                ['id' => 1],
                ['price' => $request->price]
            );

            return response()->json(['message' => 'Price save Successfully', 'data' => $data], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price = Price::find($id);

        return response()->json(['result' => $price], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Price = Price::find($id);
        if ($Price) {
            Price::where('id', $id)
            ->update([
                'price' => $request->price,
            ]);
            $Price = Price::find($id);

            return response([
                'data' => $Price,
                'message' => 'Price edit Successfully',
                'status' => 'success',
            ], 201);
        } else {
            return response([
                'message' => 'Price not found',
                'status' => '404',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Price::destroy($id);

        return response()->json(['message' => 'Price delete Successfully'], 200);
    }
}
