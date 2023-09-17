<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeverageController;
use App\Http\Controllers\BeveragesCategoryController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\IngredientStockController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTransactionController;
use App\Http\Controllers\RecipeIngridientStockController;
use App\Http\Controllers\RecipesController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RoleController;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Public Access=============================================================================================
//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//Recipes
Route::get('/data-recipe',[RecipesController::class,'index']);
//users
Route::get('/data-user',[RegistrationController::class, 'getAllData']);
Route::post('/regis-user',[RegistrationController::class, 'registration']);


//beverage category
Route::get('/data-beverage-category',[BeveragesCategoryController::class,'index']);


//Ingredient
Route::get('/data-ingredient',[IngredientController::class,'index']);


//Beverage
Route::get('/data-beverage',[BeverageController::class,'index']);


//Ingredient Stock
Route::get('/data-ingredient-stock',[IngredientStockController::class,'index']);


//Recipe Ingredient Stock
Route::get('/data-recipe-ingredient-stock',[RecipeIngridientStockController::class,'index']);




//Private Access============================================================================================
Route::group((['middleware' => ['auth:sanctum']]),function (){
//Recipes
Route::post('/store-data-recipe',[RecipesController::class,'store']);
Route::put('/update-data-recipe/{id}',[RecipesController::class,'update']);
Route::get('/search-data-recipe/{name}',[RecipesController::class,'search']);

//Role
Route::post('/create-role', [RoleController::class, 'store']);
Route::get('/data-role', [RoleController::class, 'index']);
Route::get('/data-role/{id}', [RoleController::class, 'show']);
Route::put('/update-role/{id}', [RoleController::class, 'update']);
Route::delete('/delete-role/{id}', [RoleController::class, 'destroy']);
Route::get('/data-role-kasir/{id}', [RoleController::class, 'getUserRole']);

Route::post('/store-data-beverage-category',[BeveragesCategoryController::class,'store']);
Route::put('/update-data-beverage-category/{id}',[BeveragesCategoryController::class,'update']);

Route::post('/store-data-ingredient',[IngredientController::class,'store']);
Route::put('/update-data-ingredient/{id}',[IngredientController::class,'update']);

Route::post('/store-data-beverage',[BeverageController::class,'store']);
Route::put('/update-data-beverage/{id}',[BeverageController::class,'update']);

Route::post('/store-data-ingredient-stock',[IngredientStockController::class,'store']);
Route::put('/update-data-ingredient-stock/{id}',[IngredientStockController::class,'update']);

Route::post('/store-data-recipe-ingredient-stock',[RecipeIngridientStockController::class,'store']);
Route::put('/update-data-recipe-ingredient-stock/{id}',[RecipeIngridientStockController::class,'update']);

//Order
Route::get('/data-order', [OrderController::class, 'index']);
Route::post('/store-order', [OrderController::class, 'store']);
Route::get('/show-order/{id}', [OrderController::class, 'show']);

//Transaction
Route::post('/store-order-transaction', [OrderTransactionController::class, 'store']);


Route::post('/logout', [AuthController::class, 'logout']);

});













//iseng coba coba
Route::get('/getOrderJson', function(){
    $orders = Order::all(); // Gantilah ini dengan cara Anda mengambil daftar pesanan

    $formattedOrders = [];

    foreach ($orders as $order) {
        $formattedOrder = [
            'id' => $order->id,
            'status' => $order->status,
            'total' => $order->order_details->sum('subtotal'),
            'order_details' => [],
        ];

        foreach ($order->order_details as $item) {
            $formattedOrder['order_details'][] = [
                'nama_produk' => $item->beverage->name,
                'quantity' => $item->qty,
                'subtotal' => $item->subtotal,
                'detail_ingredient' => [],
            ];

            foreach ($item->beverage->rc->ris as $itembv) {
                $formattedOrder['order_details'][count($formattedOrder['order_details']) - 1]['detail_ingredient'][] = [
                    'jumlah' => $itembv->amount * $item->qty,
                    'bahan' => $itembv->ingredient_stock->ingredient->name,
                ];
            }
        }

        $formattedOrders[] = $formattedOrder;
    }

    return response()->json($formattedOrders);
});