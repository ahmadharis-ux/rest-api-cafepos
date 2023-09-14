<?php

namespace App\Http\Controllers;

use App\Models\IngredientStock;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required',
            'amount' => 'required',
        ]);
        
        // Mulai transaksi database
        DB::beginTransaction();
        
        try {
            $order_id = $validatedData['order_id'];
            $amount = $validatedData['amount'];
        
            // Hitung total pesanan
            $total = OrderDetail::where('order_id', $order_id)->sum('subtotal');
        
            if ($amount == $total) {
                // Ubah status menjadi "Paid"
                Order::findOrFail($order_id)->update([
                    'status' => 'Paid'
                ]);

                // Simpan data transaksi
                OrderTransaction::create([
                    'order_id' => $order_id,
                    'amount' => $amount,
                ]);
        
                // Ambil daftar bahan dari pesanan
                $ingredient = [];
                $orders = Order::where('id', $order_id)->get();
        
                foreach ($orders as $order) {
                    foreach ($order->order_details as $item) {
                        foreach ($item->beverage->rc->ris as $itembv) {
                            $ingredient[] = [
                                'amount' => $itembv->amount * $item->qty,
                                'id' => $itembv->ingredient_stock->ingredient->id,
                            ];
                        }
                    }
                }
        
                // Kurangkan stok di tabel ingredient_stock
                foreach ($ingredient as $ingredientItem) {
                    $ingredientStock = IngredientStock::where('ingredients_id', $ingredientItem['id'])->first();
        
                    if ($ingredientStock) {
                        $newStock = $ingredientStock->stock - $ingredientItem['amount'];
        
                        // Pastikan stok tidak menjadi negatif
                        if ($newStock >= 0) {
                            $ingredientStock->update([
                                'stock' => $newStock
                            ]);
                        } else {
                            // Batalkan transaksi jika stok habis
                            DB::rollback();
                            return response()->json([
                                'message' => 'Stok bahan habis.'
                            ]);
                        }
                    }
                }
        
                // Commit transaksi jika semua perubahan berhasil
                DB::commit();
        
                return response()->json([
                    'message' => 'Lunas'
                ]);
            }
        
            // Jika jumlah yang dibayar tidak cocok dengan total pesanan
            DB::rollback();
            return response()->json([
                'message' => 'Jumlah pembayaran tidak sesuai.'
            ]);
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan saat melakukan transaksi.',
                'error' => $e->getMessage() // Menambahkan pesan kesalahan dalam respons
            ]);
        }
        
    }

    
    /**
     * Display the specified resource.
     */
    public function show(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderTransaction $orderTransaction)
    {
        //
    }
}
