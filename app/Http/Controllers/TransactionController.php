<?php

namespace App\Http\Controllers;

use App\Models\DiscountProduct;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $transaction;

    public function __construct()
    {
        $this->transaction = new Transaction();
    }

    public function index(Request $request) {
        $data = array(
          'products' => $this->fetchProducts(),
          'outlets' => $this->fetchOutlets()
        );
        return view('transactionView')->with($data);
    }


    public function fetch(Request $request) {
        try {
            $keyword = $_GET['search']['value'];
            $recordsTotal = Transaction::count();
            $recordsFiltered = Transaction::count();
            $raw = Transaction::where('kode_outlet', 'like', '%' .  $keyword . '%')->get();
            $data=array();
            foreach ($raw as $val) {
                $dataTemp = array(
                    'id' => $val->id,
                    'nama_outlet' => $val->kode_outlet,
                    'nama_produk' => $val->kode_produk,
                    'jumlah' => $val->jumlah,
                    'diskon' => $val->diskon,
                    'harga' => $val->totalbayar,
                );
                array_push($data, $dataTemp);
            }

            $output = array(
                "draw" => $request->draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data,
            );

            return $output;
        } catch (\Exception $e) {

        }

    }

    public function getOutletDiscountAvail(Request $request) {
        $outlet = Outlet::where('kode_outlet',$request->kode_outlet)->with('discountOutlet')->first();

        if($outlet->discountOutlet == null) {
            return response()->json(
                array(
                    'from' => '',
                    'to' => '',
                    'avail' => 0
                )
            );
        }

        $transactionData = date('Y-m-d');
        $fromDate = date('Y-m-d', strtotime($outlet->discountOutlet->awal));
        $toDate = date('Y-m-d', strtotime($outlet->discountOutlet->akhir));


        if(($transactionData >= $fromDate) && ($transactionData <= $toDate)) {
            $outletDiscountAvail = 1;
        } else {
            $outletDiscountAvail = 0;
        }

        return response()->json(
            array(
                'from' => $outlet->discountOutlet->awal,
                'to' => $outlet->discountOutlet->akhir,
                'avail' => $outletDiscountAvail
            )
        );
    }

    public function getProductDiscount(Request $request) {
        $key = $request->kode_produk;
        $discount = Product::where('kode_produk', $key)->with('discountProduct')->first();

        return response()->json($discount);
    }

    public function fetchProducts() {
        $product = new Product();

        return $product->get();
    }

    public function fetchOutlets() {
        $outlet = new Outlet();

        return $outlet->get();
    }

    public function addTransaction(Request $request) {
        try {
            $this->transaction->kode_outlet = $request->kodeOutlet;
            $this->transaction->kode_produk = $request->kodeProduk;
            $this->transaction->jumlah = $request->jumlah;
            $this->transaction->diskon = $request->diskon;
            $this->transaction->totalbayar = $request->total;
            $this->transaction->save();

            if($this->transaction->id > 0) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Transaction Created'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot create product.'
                ));
            }

        } catch (\Exception $e) {
            return response()->json(array(
                'status' => 'fail',
                'message' => $e->getMessage()
            ));
        }
    }
}
