<?php

use App\Models\Ingredient;
use App\Models\IngredientStock;
use App\Models\Recipes;
use App\Models\Beverage;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index',[
        'IngStocks' => IngredientStock::all(),
        'Recipes' => Recipes::with('ris')->get(),
        'Beverages' => Beverage::all(),
        'Orders' => Order::all(),
        'OrderDetails' => OrderDetail::all(),
    ]);
});
Route::get('/hm',function(){
    // $data = OrderDetail::where('order_id',1)->get();
    // $status = Order::select('status')->where('id', 1)->get();
    // $total = OrderDetail::where('order_id',1)->sum('subtotal');

    // $order =  OrderDetail::where('order_id',1)->get();
    // $ingredient = OrderDetail::where('order_id',1)->get();

    // $statusArray1 = [];
    // $statusArray2 = [];
    
    // foreach ($ingredient as $item) {
    //     if (isset($item->beverage->rc->ris)) {
    //         $statusArray1[] = $item->beverage->rc->ris;
    //     }
    // }
    
    // foreach ($statusArray1 as $itembv) {
    //     if (isset($itembv->ingredient_stock)) {
    //         $statusArray2[] = $itembv->ingredient_stock;
    //     }
    // }
    // return [
    //     "data Order" => $data, 
    //     "Status Pembayaran" => $status, 
    //     "Total Pembayaran" => $total,
    //     "Ingredeint" => $statusArray1
    // ];
        $ingredient = [];
    
        $orders = Order::all(); // Gantilah ini dengan cara Anda mengambil daftar pesanan
    
        foreach ($orders as $order) {
            foreach ($order->order_details as $item) {
                foreach ($item->beverage->rc->ris as $itembv) {
                    $ingredient[] = [
                        'amount' => $itembv->amount * $item->qty,
                        'name' => $itembv->ingredient_stock->ingredient->name,
                    ];
                }
            }
        }
    
        return $ingredient;
    
    
});
