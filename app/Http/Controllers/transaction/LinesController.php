<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLineGroup;
use App\Models\MstrMaterial;
use App\Models\MstrLine;
use GuzzleHttp\Client;
use App\Models\MstrConsumable;
use App\Models\MstrGroup;

class LinesController extends Controller
{
    public function indexGroup()
    {

        $lines = MstrLineGroup::with('group')->get();




        return view('transaction.group', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function indexLine($id)
    {


        $lg = MstrLine::where('Ln_lgId', $id)->get();


        return view('transaction.line', compact('lg', 'id'));
    }

    public function searchMaterial(Request $request)
    {



        $search = $request->input('search');
        $id = $request->input('id'); // Ambil 'id' dari request


        if (empty($search)) {
            // Jika tidak ada pencarian, ambil semua material berdasarkan mt_lgId
            $materials = MstrLine::where('Ln_lgId', $id)->get();
        } else {
            // Jika ada pencarian, filter berdasarkan mt_number
            $materials = MstrLine::where('Ln_lgId', $id)
                ->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(Ln_name) like ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('Ln_lgId like ?', ['%' . strtolower($search) . '%']);
                })
                ->get();
        }



        return response()->json($materials);
    }


    public function indexConsumable($lines, $material)
    {


        $id = $material;



        // $consumables = MstrConsumable::with(['material.masterLineGroup.plan', 'material.masterLineGroup.costCenter']) // Muat relasi hingga masterLineGroup
        //     ->where('Cb_mtId', $material)->get();
        // $materials = MstrConsumable::with(['masterLineGroup.plan', 'masterLineGroup.costCenter', 'masterLineGroup.lines'])

        //     ->whereHas('masterLineGroup.lines', function ($query) use ($material) {
        //         $query->where('_id', $material);
        //     })->where('Cb_lgId', $lines)
        //     ->get();
        // dd($materials);


        // foreach ($materials as $material) {
        //     dd($material->masterLineGroup->lines);
        // }

        $materials = MstrLine::with(['lineGroup.plan', 'lineGroup.costCenter', 'lineGroup.consumable'])->where('_id', $material)->first();





        return view('transaction.consumable', compact('materials', 'id'));


    }


    public function searchConsumable(Request $request)
    {
        $search = $request->input('search');


        $id = $request->input('id');

        if (empty($search)) {
            $materials = [];
        } else {
            $materials = MstrConsumable::
                where(function ($query) use ($search) {
                    $query->where('Cb_number', 'like', '%' . $search . '%')
                        ->orWhere('Cb_desc', 'like', '%' . $search . '%');
                })->limit(40)
                ->get();


        }




        return response()->json($materials);
    }






    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(MstrLine $line)
    {

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



    public function destroy(MstrLine $line)
    {
        $line->delete();

        return redirect()->route('MasterLine.index')->with('success', 'Line deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');


        if (empty($search)) {
            $lines = MstrLineGroup::with('group', 'lines')->get();

        } else {
            $lines = MstrLineGroup::with('group', 'lines')
                ->whereHas('group', function ($query) use ($search) {
                    $query->where('Gr_name', 'like', '%' . $search . '%');
                })
                ->get();
        }


        return response()->json($lines);
    }

}