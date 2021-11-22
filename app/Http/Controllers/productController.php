<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    public function index() {
        return view ('productView');
    }

    public function fetch(Request $request) {
        try {
            $keyword = $_GET['search']['value'];
            $recordsTotal = Product::count();
            $recordsFiltered = Product::count();
            $raw = Product::where('nama_produk', 'like', '%' .  $keyword . '%')->get();
            $data=array();
            foreach ($raw as $val) {
                $dataTemp = array(
                    'id' => $val->id,
                    'nama_produk' => $val->nama_produk,
                    'kode_produk' => $val->kode_produk,
                    'harga' => $val->harga,
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

    public function get(Request $request) {
        try {
            $product = $this->product->find($request->id);

            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }

    }

    public function create(Request $request) {
        try {
            $this->product->kode_produk = $request->kodeProduk;
            $this->product->nama_produk = $request->namaProduk;
            $this->product->harga = $request->hargaProduk;
            $this->product->save();

            if($this->product->id > 0) {
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

    public function update(Request $request) {
        try {
            $product = $this->product->find($request->id);
            $product->kode_produk = $request->kodeProduk;
            $product->nama_produk = $request->namaProduk;
            $product->harga = $request->harga;

            if($product->save()) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Product Updated'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot update product.'
                ));
            }

        } catch (\Exception $e) {
            return response()->json(array(
                'status' => 'fail',
                'message' => $e->getMessage()
            ));
        }
    }

    public function delete(Request $request) {
        try {
            $product = $this->product->find($request->id);

            if($product->delete()) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Product Deleted'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot delete product.'
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
