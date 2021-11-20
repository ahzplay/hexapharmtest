<?php

namespace App\Http\Controllers;

use App\Models\DiscountProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountProductController extends Controller
{
    private $discount;

    public function __construct()
    {
        $this->discount = new DiscountProduct();
    }

    public function index() {
        $data = array (
            'products' => $this->fetchProducts()
        );
        return view ('discountProductView')->with($data);
    }

    public function fetchProducts() {
        $product = new Product();

        return $product->get();
    }

    public function fecthDiscountOutlets(Request $request) {
        try {
            $keyword = $_GET['search']['value'];
            $recordsTotal = DiscountProduct::count();
            $recordsFiltered = DiscountProduct::count();
            $raw = DiscountProduct::whereHas('product', function($q) use($keyword){
                return $q->where('kode_produk', 'like', '%' .  $keyword . '%');
            })->with('product')->get();
            $data=array();
            foreach ($raw as $val) {
                $dataTemp = array(
                    'id' => $val->id,
                    'no_surat' => $val->no_surat,
                    'kode_produk' => $val->kode_produk,
                    'nama_produk' => $val->product->nama_produk,
                    'diskon_val' => $val->discount,
                    'max' => $val->max,
                    'min' => $val->min,
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

    public function createDiscount(Request $request) {
        $existRow = $this->discount->where('kode_produk',$request->kodeProduk)->count();

        if($existRow > 0) {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Outlet already added'
            ));
        }

        try {
            $this->discount->no_surat = $request->noSurat;
            $this->discount->kode_produk = $request->kodeProduk;
            $this->discount->discount = $request->diskonVal;
            $this->discount->max = $request->max;
            $this->discount->min = $request->min;
            $this->discount->save();

            if($this->discount->id > 0) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Product Created'
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
