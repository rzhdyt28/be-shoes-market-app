<?php

namespace App\Http\Controllers\API;

use App\Hellper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukTransactionController extends Controller
{
    public function fetchData(Request $request){

        $id = $request->input('id');
        $limit = $request->input('limit', 5);
        $status = $request->input('status');

        // Get data sort by Id
        if($id) {
            $transaction = Transaction::with(['transaction_details.product'])->find($id);

            if ($transaction) {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil diambil');
            }else{
                return  ResponseFormatter::error(null,'Data transaksi tidak ada', 404);
            }
        }

        $transaction = Transaction::with(['transaction_details.product'])->where('users_id', Auth::user()->id);

        if ($status) {
            $transaction->where('status', $status);
        }
        return ResponseFormatter::success($transaction->paginate($limit), 'Data berhasil diambil');
    }

    public function checkOut(Request $request) {
        $request->validate([
            'transaction_details' => 'required|array',
            'transaction_details.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING, SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED',
        ]);

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status,
        ]);

        foreach ($request->transaction_details as $product) {
            TransactionDetail::create([
                'users_id' => Auth::user()->id,
                'products_id'=>$product['id'],
                'transactions_id'=>$transaction->id,
                'quantity' => $product['quantity']
            ]);
        }

        return ResponseFormatter::success($transaction->load('transaction_details.product'), 'Transaksi Berhasil');
    }
}
