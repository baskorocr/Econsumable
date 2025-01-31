<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
use App\Models\MstrLine;
use App\Models\MstrMaterial;
use App\Models\OrderSegment;
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


        // dd($segment->masterLineGroup->leader->npk);
        foreach ($data as $key => $value) {
            if (is_array($value) && isset($value['quantity']) && $value['quantity'] == 0) {
                unset($data[$key]);
            }
        }



        $segment = MstrLine::with(['lineGroup.group', 'lineGroup.leader', 'lineGroup.section', 'lineGroup.pjStock'])
            ->findOrFail($request->idMt);






        $generate = GenerateCustomID($segment->lineGroup->group->Gr_segment);



        $filteredData = array_filter($data, function ($consumable) {
            return is_array($consumable) && isset($consumable['quantity']) && $consumable['quantity'] > 0;
        });

        if (empty($filteredData)) {
            Alert::error('Warning', "Quantity Consumable is Empty");
            return redirect()->back();
        }



        // Output hasil setelah filter
        foreach ($filteredData as $key => $consumable) {



            try {
                $requestId = MstrAppr::create([
                    'no_order' => $generate,
                    'ConsumableId' => $consumable['id'],
                    'jumlah' => $consumable['quantity'],

                    'NpkDept' => $segment->lineGroup->leader?->npk ?? null,

                    'NpkSect' => $segment->lineGroup->section?->npk ?? null,
                    'NpkPj' => $segment->lineGroup->pjStock?->npk ?? null,
                    'ApprDeptDate' => null,
                    'ApprPjStokDate' => null,
                    'ApprSectDate' => null,
                    'lineFrom' => $segment->Ln_name,
                    'token' => Str::uuid()->toString()

                ]);
                $requestId->load('OrderSegment');




                if (isset($segment->masterLineGroup->section)) {

                    SendWa($segment->masterLineGroup->section->noHp, $segment->masterLineGroup->section->name, $requestId->orderSegment->noOrder, $requestId->NpkSect, $requestId->token);
                }

                Alert::success('Transaction Success', 'Approval application is in progress, please check your dashboard periodically');


            } catch (\Exception $e) {
                dd($e);
                Alert::error('Transaction failed', $e->getMessage());
                return redirect()->route('listLine');
            }


        }



        return redirect()->route('listGroup');



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