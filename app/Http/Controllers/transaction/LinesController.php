<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLineGroup;
use App\Models\MstrMaterial;
use App\Models\MstrLine;
use GuzzleHttp\Client;
use App\Models\MstrConsumable;
class LinesController extends Controller
{
    public function indexLine()
    {

        $lines = MstrLineGroup::with('group', 'line')->get();


        return view('transaction.line', compact('lines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function indexMaterial($id)
    {
        $materials = MstrMaterial::where('mt_lgId', $id)->get();


        return view('transaction.material', compact('materials', 'id'));
    }

    public function searchMaterial(Request $request)
    {

        $search = $request->input('search');
        $id = $request->input('id'); // Ambil 'id' dari request

        if (empty($search)) {
            // Jika tidak ada pencarian, ambil semua material berdasarkan mt_lgId
            $materials = MstrMaterial::where('mt_lgId', $id)->get();
        } else {
            // Jika ada pencarian, filter berdasarkan mt_number
            $materials = MstrMaterial::where('mt_lgId', $id)
                ->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(mt_number) like ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('MT_desc like ?', ['%' . strtolower($search) . '%']);
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
        $materials = MstrMaterial::with(['masterLineGroup.plan', 'masterLineGroup.costCenter', 'consumables'])->where('Mt_lgId', $lines)->where('_id', $material)->get();



        return view('transaction.consumable', compact('materials', 'id'));


    }


    public function searchConsumable(Request $request)
    {
        $search = $request->input('search');
        $id = $request->input('id');

        if (empty($search)) {
            $materials = [];
        } else {
            $materials = MstrConsumable::where('Cb_mtId', 'like', '%' . $id . '%')
                ->where(function ($query) use ($search) {
                    $query->where('Cb_number', 'like', '%' . $search . '%')
                        ->orWhere('Cb_desc', 'like', '%' . $search . '%');
                })
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

        $lines = MstrLineGroup::with('group', 'line')
            ->whereHas('group', function ($query) use ($search) {
                $query->where('Gr_name', 'like', '%' . $search . '%');
            })
            ->get();

        return response()->json($lines);
    }

}