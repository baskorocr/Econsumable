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
    public function indexGroup(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input('search');

            // Query awal dengan relasi 'group'
            $query = MstrLineGroup::with('group');

            // Jika ada pencarian, filter berdasarkan nama grup
            if (!empty($search)) {
                $query->whereHas('group', function ($q) use ($search) {
                    $q->whereRaw('LOWER(Gr_name) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            }

            // Kembalikan JSON untuk AJAX
            return response()->json($query->get());
        }

        // Jika bukan AJAX, ambil semua data dan tampilkan view
        $lines = MstrLineGroup::with('group')->get();
        return view('transaction.group', compact('lines'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function indexLine(Request $request, $id)
    {
        // Jika request adalah AJAX, kembalikan JSON
        if ($request->ajax()) {
            $search = $request->input('search');

            // Query awal untuk mencari berdasarkan Ln_lgId
            $query = MstrLine::where('Ln_lgId', $id);

            // Jika ada pencarian, tambahkan kondisi tambahan
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(Ln_name) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('Ln_lgId LIKE ?', ['%' . strtolower($search) . '%']);
                });
            }

            // Ambil hasil query
            $materials = $query->get();

            // Kembalikan dalam format JSON untuk AJAX
            return response()->json($materials);
        }

        // Jika request bukan AJAX, tampilkan view
        $lg = MstrLine::where('Ln_lgId', $id)->get();
        return view('transaction.line', compact('lg', 'id'));
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

        $materials = MstrLine::with(['lineGroup.plan', 'lineGroup.costCenter', 'lineGroup.consumable'])->where('_id', $material)->where('Ln_lgId', $lines)->first();





        return view('transaction.consumable', compact('materials', 'id'));


    }


    public function searchConsumable(Request $request)
    {
        $search = $request->input('search');


        $id = $request->input('id');
        $lg = $request->input('lnGroup');




        if (empty($search)) {
            $materials = [];
        } else {
            $materials = MstrConsumable::with([
                'masterLineGroup.lines' => function ($query) use ($id) {
                    $query->where('_id', $id);
                }
            ])
                ->where(function ($query) use ($search) {
                    $query->where('Cb_number', 'like', '%' . $search . '%')
                        ->orWhere('Cb_desc', 'like', '%' . $search . '%');
                })
                ->where('Cb_lgId', $lg) // Ensure $lg is properly defined
                ->limit(40)
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



}