<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
use App\Models\MstrMaterial;
use App\Models\orderSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
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
        $data = $request->all();
        $filteredConsumables = [];



        $segment = MstrMaterial::with(['masterLineGroup.group', 'masterLineGroup.leader', 'masterLineGroup.section', 'masterLineGroup.pjStock'])
            ->findOrFail($request->idMt);



        // dd($segment->masterLineGroup->leader->npk);
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value['quantity']) && $value['quantity'] == 0) {
                unset($data[$key]);
            }
        }
        // Output array setelah item dengan quantity 0 dihapus


        // Cek jika consumables ada dalam request
        foreach ($data as $key => $value) {
            // Pastikan key adalah consumables dan memiliki quantity
            if (strpos($key, 'consumables') !== false && isset($value['quantity']) && $value['quantity'] > 0) {
                // Simpan consumable yang quantity-nya lebih besar dari 0
                $filteredConsumables[$key] = $value;
            }
        }

        $generate = generateCustomID($segment->masterLineGroup->group->Gr_segment);





        // Output hasil setelah filter
        foreach ($filteredConsumables as $key => $consumable) {

            // // Cek apakah quantity lebih dari 0
            // if ($consumable['quantity'] > 0) {
            //     // Lakukan sesuatu dengan data consumable
            //     echo "ID: " . $consumable['id'] . " - Quantity: " . $consumable['quantity'] . "\n";
            // }
            try {
                $requestId = MstrAppr::create([
                    'no_order' => $generate,
                    'ConsumableId' => $consumable['id'],
                    'jumlah' => $consumable['quantity'],
                    'NpkUser' => auth()->user()->npk,
                    'NpkDept' => $segment->masterLineGroup->leader->npk,
                    'NpkSect' => $segment->masterLineGroup->section->npk,
                    'NpkPj' => $segment->masterLineGroup->pjStock->npk ?: null,
                    'ApprDeptDate' => null,
                    'ApprPjStokDate' => null,
                    'ApprSectDate' => null,
                    'token' => Str::uuid()->toString()

                ]);
                $requestId->load('orderSegment', 'user');



                if ($segment->masterLineGroup->section->noHp !== null) {


                    sendWa($segment->masterLineGroup->section->noHp, $segment->masterLineGroup->section->name, $requestId->orderSegment->noOrder, $requestId->user->name, $requestId->token);
                }

                Alert::success('Transaction Success', 'Approval application is in progress, please check your dashboard periodically');


            } catch (\Exception $e) {
                Alert::error('Transaction failed', $e->getMessage());
                return redirect()->route('listLine');
            }


        }



        return redirect()->route('listLine');



    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}