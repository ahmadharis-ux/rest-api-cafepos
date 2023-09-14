<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Beverage;
use App\Models\BeveragesCategory;
use App\Models\Ingredient;
use App\Models\IngredientStock;
use App\Models\RecipeIngridientStock;
use App\Models\Recipes;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Role::create([
            'name' => 'Kasir',
        ]);
        User::create([
            'name' => 'Rahman',
            'email' => 'rahman@gmail.com',
            'password' => Hash::make('1234567'),
            'role_id' => '1',
        ]);
        $ingredients = [  
            ['name'=>'Gula'],
            ['name'=>'Es'],
            ['name'=>'Air'],
            ['name'=>'Teh'],
            ['name'=>'Kopi'],
        ];
        foreach($ingredients as $ing){
            Ingredient::create([
                'name' => $ing['name'],
            ]);
        }

        $recipes = [  
            ['name'=>'Es Teh Manis'],
            ['name'=>'Es Kopi'],
        ];
        foreach($recipes as $recipe){
            Recipes::create([
                'name' => $recipe['name'],
            ]);
        }

        $BevCars = [  
            ['name'=>' Teh'],
            ['name'=>' Fresh Juice'],
            ['name'=>' Kopi'],
        ];
        foreach($BevCars as $bev){
            BeveragesCategory::create([
                'name' => $bev['name'],
            ]);
        }

        $IngStocks = [  
            ['ingredients_id'=>1,'stock'=> 1000],
            ['ingredients_id'=>2,'stock'=> 1000],
            ['ingredients_id'=>3,'stock'=> 10000],
            ['ingredients_id'=>4,'stock'=> 100],
            ['ingredients_id'=>5,'stock'=> 200],
        ];
        foreach($IngStocks as $ingstock){
            IngredientStock::create([
                'ingredients_id' => $ingstock['ingredients_id'],
                'stock' => $ingstock['stock'],
            ]);
        }

        $RecIngStocks = [  
            ['recipe_id'=>1,'ingredient_stock_id'=>1,'amount'=> 100],
            ['recipe_id'=>1,'ingredient_stock_id'=>2,'amount'=> 10],
            ['recipe_id'=>1,'ingredient_stock_id'=>3,'amount'=> 50],
            ['recipe_id'=>1,'ingredient_stock_id'=>4,'amount'=> 20],
            ['recipe_id'=>2,'ingredient_stock_id'=>5,'amount'=> 20],
            ['recipe_id'=>2,'ingredient_stock_id'=>3,'amount'=> 60],
            ['recipe_id'=>2,'ingredient_stock_id'=>2,'amount'=> 15],
        ];
        foreach($RecIngStocks as $recingstock){
            RecipeIngridientStock::create([
                'recipe_id' => $recingstock['recipe_id'],
                'ingredient_stock_id' => $recingstock['ingredient_stock_id'],
                'amount' => $recingstock['amount'],
            ]);
        }

        $Beverages = [  
            ['name' => 'Es Teh Manis', 'beverage_category_id'=>1,'recipe_id'=>1,'price'=> 7000,'netto' => null],
            ['name' => 'Kopi Hitam', 'beverage_category_id'=>3,'recipe_id'=>2,'price'=> 15000,'netto' => null],
        ];
        foreach($Beverages as $beverage){
            Beverage::create([
                'name' => $beverage['name'],
                'beverage_category_id' => $beverage['beverage_category_id'],
                'recipe_id' => $beverage['recipe_id'],
                'price' => $beverage['price'],
                'netto' => $beverage['netto'],
            ]);
        }
    }
}
