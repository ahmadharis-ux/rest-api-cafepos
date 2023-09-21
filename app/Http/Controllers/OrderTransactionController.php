<?php

namespace App\Http\Controllers;

use App\Models\IngredientStock;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;

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
                        foreach ($item->beverage->recipe->ris as $itembv) {
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
        
                return response($orders);
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
    // trial 1
    // public function cetakstruk(){
    //     try {
    //         $profile = CapabilityProfile::load("SP2000");
    //         $connector = new WindowsPrintConnector("smb://computer/printer"); // Sesuaikan dengan nama printer yang tertera di propetis
    //         $printer = new Printer($connector, $profile);
    
    //         // Sekarang Anda dapat mencetak struk di sini
    //         $printer->text("Hello, ini adalah contoh cetak struk.");
    //         $printer->cut();
    //         $printer->close();
    //     } catch (Exception $e) {
    //         echo "Terjadi kesalahan: " . $e->getMessage();
    //     }
    // }
    public function cetakstruk($id){
        function buatBaris1Kolom($kolom1)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 33;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = count($kolom1Array);

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris) . "\n";
        }

        function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
        {
            // Mengatur lebar setiap kolom (dalam satuan karakter)
            $lebar_kolom_1 = 11;
            $lebar_kolom_2 = 11;
            $lebar_kolom_3 = 11;

            // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
            $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
            $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
            $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

            // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
            $kolom1Array = explode("\n", $kolom1);
            $kolom2Array = explode("\n", $kolom2);
            $kolom3Array = explode("\n", $kolom3);

            // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
            $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

            // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
            $hasilBaris = array();

            // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
            for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

                // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
                $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
                $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

                $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

                // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
            }

            // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
            return implode($hasilBaris) . "\n";
        }
            $profile = CapabilityProfile::load("SP2000");
            $connector = new WindowsPrintConnector("smb://computer/printer"); // Sesuaikan dengan nama printer yang tertera di propetis
            $printer = new Printer($connector, $profile);
    
            $idOrder = Order::findOrFail($id);
            $order = Order::with('order_details')->get();

            $printer->initialize();
            $printer->selectPrintMode(Printer::MODE_FONT_A);
            $printer->text(buatBaris1Kolom("Sela Kopi"));
            $printer->text(buatBaris1Kolom("Cianjur, Telp 62xxxxx"));
            $printer->text(buatBaris1Kolom("Faktur: $idOrder"));
            $printer->text(buatBaris1Kolom("Tanggal: $idOrder->created_at"));

            $printer->text(buatBaris1Kolom("---------------------------------"));

            $orderDetail = OrderDetail::where('order_id', $id)->get();

            foreach ($orderDetail as $detail) {
                $printer->text(buatBaris1Kolom("Nama Produk: " . $detail->beverage->name));
                $printer->text(buatBaris1Kolom("Harga: " . $detail->beverage->price));
                $printer->text(buatBaris1Kolom("Jumlah: " . $detail->qty));
                // Tambahkan informasi lainnya sesuai kebutuhan
                $printer->text(buatBaris1Kolom("---------------------------------"));
            }

            $printer->text(buatBaris1Kolom("---------------------------------"));
            $printer->text(buatBaris1Kolom("---------------------------------"));
            
            // Sekarang Anda dapat mencetak struk di sini
            $printer->text("Hello, ini adalah contoh cetak struk.");
            $printer->cut();
            $printer->close();
            return response($printer);
        
    }
}
