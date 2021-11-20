<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    private $outlet;

    public function __construct()
    {
        $this->outlet = new Outlet();
    }

    public function index() {
        return view ('outletView');
    }

    public function callAll() {
        $outlets = $this->outlet->with('discountOutlet')->get();
        return response()->json($outlets);
    }

    public function fetch(Request $request) {
        try {
            $keyword = $_GET['search']['value'];
            $recordsTotal = Outlet::count();
            $recordsFiltered = Outlet::count();
            $raw = Outlet::where('nama_outlet', 'like', '%' .  $keyword . '%')->get();
            $data=array();
            foreach ($raw as $val) {
                $dataTemp = array(
                    'id' => $val->id,
                    'kode_outlet' => $val->kode_outlet,
                    'nama_outlet' => $val->nama_outlet,
                    'alamat' => $val->alamat,
                    'aktif' => $val->aktif,
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
            $outlet = $this->outlet->find($request->id);

            return response()->json($outlet);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }

    }

    public function create(Request $request) {
        try {
            $this->outlet->kode_outlet = $request->kodeOutlet;
            $this->outlet->nama_outlet = $request->namaOutlet;
            $this->outlet->alamat = $request->alamat;
            $this->outlet->aktif = $request->aktif;
            $this->outlet->save();

            if($this->outlet->id > 0) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Outlet Created'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot create outlet.'
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
            $outlet = $this->outlet->find($request->id);
            $outlet->kode_outlet = $request->kodeOutlet;
            $outlet->nama_outlet = $request->namaOutlet;
            $outlet->alamat = $request->alamat;
            $outlet->aktif = $request->aktif;

            if($outlet->save()) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Outlet Updated'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot update outlet.'
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
            $outlet = $this->outlet->find($request->id);

            if($outlet->delete()) {
                return response()->json(array(
                    'status' => 'success',
                    'message' => 'Outlet Deleted'
                ));
            } else {
                return response()->json(array(
                    'status' => 'fail',
                    'message' => 'Cannot delete outlet.'
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
