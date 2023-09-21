<?php

namespace App\Http\Controllers;

use App\Models\Beverage;
use App\Models\IngredientStock;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(Order::with('order_details.beverage')->get());
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
            'order.status' => 'required',
            'order.author_id' => 'required|integer',
            'order_details.*.beverage_id' => 'required|integer',
            'order_details.*.qty' => 'required|integer',
        ]);
        
        $order = Order::create([
            'status' => $validatedData['order']['status'],
            'author_id' => $validatedData['order']['author_id'],
        ]);
        
        $order_id = $order->id;
        
        $orderDetails = [];
        
        foreach ($validatedData['order_details'] as $data) {
            $beverage = Beverage::find($data['beverage_id']); // Gantilah 'Beverage' dengan nama model yang sesuai
            $subtotal = $beverage->price * $data['qty'];
        
            $orderDetails[] = [
                'order_id' => $order_id,
                'beverage_id' => $data['beverage_id'],
                'qty' => $data['qty'],
                'subtotal' => $subtotal,
            ];
        }
        
        OrderDetail::insert($orderDetails);
        $orderWithStatus = [
            'status' => $order->status,
            'order_details' => $orderDetails,
        ];
        return response($orderWithStatus);
        
        
    }
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'order.status' => 'required',
    //         'order.author_id' => 'required|integer',
    //         'order_details.*.beverage_id' => 'required|integer',
    //         'order_details.*.qty' => 'required|integer',
    //     ]);
    
    //     // Mulai transaksi database
    //     DB::beginTransaction();
    
    //     try {
    //         $order = Order::create([
    //             'status' => $validatedData['order']['status'],
    //             'author_id' => $validatedData['order']['author_id'],
    //         ]);
    
    //         if (!$order) {
    //             // Batalkan transaksi jika gagal membuat pesanan
    //             DB::rollback();
    //             return response()->json([
    //                 'message' => 'Gagal membuat pesanan.'
    //             ]);
    //         }
    
    //         $order_id = $order->id;
    //         $orderDetails = [];
    
    //         foreach ($validatedData['order_details'] as $data) {
    //             $beverage = Beverage::find($data['beverage_id']);
    //             $subtotal = $beverage->price * $data['qty'];
    
    //             // Periksa stok bahan yang diperlukan berdasarkan resep (recipe)
    //             foreach ($beverage->rc->ris as $ingredient) {
    //                 $requiredAmount = $ingredient->amount * $data['qty'];
    //                 $ingredientStock = IngredientStock::where('ingredients_id', $ingredient->ingredient->id)->first();
    
    //                 if ($ingredientStock && $ingredientStock->stock < $requiredAmount) {
    //                     // Batalkan transaksi jika stok bahan tidak mencukupi
    //                     DB::rollback();
    //                     return response()->json([
    //                         'message' => 'Pesanan dibatalkan karena kehabisan stok bahan: ' . $ingredient->ingredient->name
    //                     ]);
    //                 }
    //             }
    
    //             $orderDetails[] = [
    //                 'order_id' => $order_id,
    //                 'beverage_id' => $data['beverage_id'],
    //                 'qty' => $data['qty'],
    //                 'subtotal' => $subtotal,
    //             ];
    //         }
    
    //         OrderDetail::insert($orderDetails);
    
    //         // Commit transaksi jika semua perubahan berhasil
    //         DB::commit();
    
    //         return response()->json([
    //             'message' => 'Berhasil Ditambahkan'
    //         ]);
    //     } catch (\Exception $e) {
    //         // Tangani kesalahan yang mungkin terjadi
    //         DB::rollback();
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan saat melakukan transaksi.',
    //             'error' => $e->getMessage() // Menambahkan pesan kesalahan dalam respons
    //         ]);
    //     }
    // }
    
    
    
    
    
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            'DetailOrder' => OrderDetail::where('order_id',$id)->get(),
            'Total' => OrderDetail::where('order_id',$id)->sum('subtotal'),
            // 'Total' => Order::findOrFail($id)->with('orderdetail')->sum('subtotal'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
