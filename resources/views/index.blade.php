<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
    <h1>Rekap Data</h1>
    <div class="row">
        <div class="col-md-3">
            <table class="table" id="display">
                <thead>
                    <tr>
                        <th>Nama Ingredient</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($IngStocks as $IngStock)
                    <tr>
                        <td>{{$IngStock->ingredient->name}}</td>
                        <td>{{$IngStock->stock}} gram</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md">
            <table class="table" id="display">
                <thead>
                    <tr>
                        <th>Nama Recipe</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Recipes as $recipe)
                    <tr>
                        <td>{{$recipe->name}}</td>
                        <td>
                            @foreach ($recipe->ris as $item)
                            <p>{{$item->amount}} gram {{$item->ingredient_stock->ingredient->name}}
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md">
            <table class="table" id="display">
                <thead>
                    <tr>
                        <th>Nama Menu</th>
                        <th>Category Menu</th>
                        <th>Buku Resep</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Beverages as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->bc->name}}</td>
                        <td>{{$item->rc->name}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-8 m-3">
            <table class="table" id="display">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Detail Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Orders as $order)
                    <tr>
                        <td>
                            {{$order->id}}<br>
                            <b>Status : {{$order->status}}</b><br>
                            <b>Total : {{$order->order_details->sum('subtotal')}}</b>
                        </td>
                        <td class="row">
                            @foreach ($order->order_details as $item)
                            <div class="card col m-2">
                                <p>
                                    <label for="">Nama Produk : {{$item->beverage->name}}</label> 
                                    <br> 
                                    <label for="">Quantity : {{$item->qty}}</label> 
                                    <br>
                                    <label for="">Subtotal : {{$item->subtotal}}</label> 
                                </p>
                            </div>
                            
                                <div class="card col m-2">
                                    <b>Detail Ingredient</b>
                                    {{-- <p>Recipe Id : {{$item->beverage->rc}}</p> --}}
                                    {{-- <hr> --}}
                                    {{-- <p>Recipe Id : {{$item->beverage->rc->ris}}</p> --}}
                                    @foreach ($item->beverage->rc->ris as $itembv)
                                        <p>
                                            
                                            <label for="">{{$itembv->amount * $item->qty}} Gram {{$itembv->ingredient_stock->ingredient->name}}</label>
                                        </p>
                                    @endforeach
                                </div>
                                    <hr>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>   
</body>
</html>