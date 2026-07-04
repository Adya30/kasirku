<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name', 'asc')->get();
        return view('cashier.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $totalPrice = 0;
            $detailsData = [];

            foreach ($request->cart as $item) {
                $product = Product::lockForUpdate()->find($item['id']);

                if ($product->stock < $item['qty']) {
                    throw new Exception("Stok produk '{$product->name}' tidak mencukupi (Tersisa: {$product->stock}).");
                }

                $subtotal = $product->price * $item['qty'];
                $totalPrice += $subtotal;

                $detailsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['qty'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                    'product_model' => $product
                ];
            }

            if ($request->amount_paid < $totalPrice) {
                throw new Exception("Nominal pembayaran kurang.");
            }

            $amountChange = $request->amount_paid - $totalPrice;

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'total_price' => $totalPrice,
                'amount_paid' => $request->amount_paid,
                'amount_change' => $amountChange,
                'user_id' => Auth::id(),
            ]);

            foreach ($detailsData as $detail) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $product = $detail['product_model'];
                $product->stock -= $detail['quantity'];
                $product->save();
            }

            DB::commit();

            $transaction->load('details.product', 'user');

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses!',
                'transaction' => $transaction
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
