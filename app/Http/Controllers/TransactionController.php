<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Response;
use Illuminate\Support\Facades\Validator;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::orderBy('time', 'DESC')->get();
        $response = [
            // pesan
            'message' => 'List Data transaction diurutkan dari yang terakhir',
            'data' => $transaction
        ];

        return response()->json($response, 200);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:expense,revenue'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $transaction = Transaction::create($request->all());
            $response = [
                'message' => 'transaction created',
                'data' => $transaction
            ];
            return response()->json($response, 200);
        } catch (QueryException $e) {
            // menangkap pesan error
            return response()->json([
                'message' => 'Failed '. $e->errorInfo
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        $response = [
            'message' => 'Detail transaction '.$id,
            'data' => $transaction
        ];
        return \response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => 'required|in:expense,revenue'
        ]);

        // jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $transaction->update($request->all());
            $response = [
                'message' => 'transaction updated',
                'data' => $transaction
            ];
            return response()->json($response, 200);
        } catch (QueryException $e) {
            // menangkap pesan error
            return response()->json([
                'message' => 'Failed '. $e->errorInfo
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = transaction::findOrFail($id);
        try {
            $transaction->delete();
            $response = [
                'message' => 'transaction deleted',
                // 'data' => $transaction
            ];
            return response()->json($response, 200);
        } catch (QueryException $e) {
            // menangkap pesan error
            return response()->json([
                'message' => 'Failed '. $e->errorInfo
            ], 422);
        }
    }
}
