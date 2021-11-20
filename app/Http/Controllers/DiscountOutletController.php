<?php

namespace App\Http\Controllers;

use App\Models\DiscountOutlet;
use App\Models\Outlet;
use Illuminate\Http\Request;

class DiscountOutletController extends Controller
{

    private $discount;

    public function __construct()
    {
        $this->discount = new DiscountOutlet();
    }

    public function index() {
        $data = array (
          'outlets' => $this->fetchOutlets()
        );
        return view ('discountOutletView')->with($data);
    }

    public function fetchOutlets() {
        $outlet = new Outlet();

        return $outlet->get();
    }

    public function fecthDiscountOutlets(Request $request) {

        try {
            $keyword = $_GET['search']['value'];
            $recordsTotal = DiscountOutlet::count();
            $recordsFiltered = DiscountOutlet::count();
            $raw = DiscountOutlet::whereHas('outlet', function($q) use($keyword){
                return $q->where('kode_outlet', 'like', '%' .  $keyword . '%');
            })->with('outlet')->get();
            $data=array();
            foreach ($raw as $val) {
                $dataTemp = array(
                    'id' => $val->id,
                    'no_surat' => $val->no_surat,
                    'kode_outlet' => $val->kode_outlet,
                    'nama_outlet' => $val->outlet->nama_outlet,
                    'awal' => $val->awal,
                    'akhir' => $val->akhir,
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
        $existRow = $this->discount->where('kode_outlet',$request->kodeOutlet)->count();

        if($existRow > 0) {
            return response()->json(array(
                'status' => 'fail',
                'message' => 'Outlet already added'
            ));
        }

        $from = strtotime($request->awal);
        $to = strtotime( $request->akhir);

        try {
            $this->discount->no_surat = $request->noSurat;
            $this->discount->kode_outlet = $request->kodeOutlet;
            $this->discount->awal = date('Y-m-d',$from);
            $this->discount->akhir = date('Y-m-d',$to);
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
