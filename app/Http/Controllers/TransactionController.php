<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Outlet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request) {
        $data = array(
          'products' => $this->fetchProducts(),
          'outlets' => $this->fetchOutlets()
        );
        return view('transactionView')->with($data);
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

    public function fetchProducts() {
        $product = new Product();

        return $product->get();
    }

    public function fetchOutlets() {
        $outlet = new Outlet();

        return $outlet->get();
    }
}
