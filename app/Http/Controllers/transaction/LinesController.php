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

    public function sapSend(Request $request)
    {
        $input = $request->all();


        // Menggabungkan consumables1, consumables2, dll. ke dalam satu array
        $consumables = [];
        foreach ($input as $key => $value) {
            if (str_starts_with($key, 'consumables')) {
                $consumables[] = $value;
            }
        }

        // Validasi jika consumables kosong
        if (empty($consumables)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada consumables yang ditemukan.'
            ], 400);
        }

        // Menyiapkan payload untuk API SAP
        $sapPayload = [
            "cons" => "X",
            "LT_INPUT" => []
        ];



        foreach ($consumables as $consumable) {
            $sapPayload['LT_INPUT'][] = [
                "MATERIAL" => $consumable['id'],
                "PLANT_ASAL" => $request->input('PlanCode'),
                "SLOC_ASAL" => $request->input('SlocId'),
                "QUANTITY" => $consumable['quantity'],
                "SATUAN" => "PCE", // Ubah ke satuan yang sesuai jika perlu
                "COST_CENTER" => $request->input('CsCode')
            ];
        }

        // Filter untuk menghapus elemen dengan QUANTITY = "0"
        $sapPayload['LT_INPUT'] = array_filter($sapPayload['LT_INPUT'], function ($item) {
            return $item['QUANTITY'] !== "0";
        });


        // Validasi jika tidak ada data setelah filter
        if (empty($sapPayload['LT_INPUT'])) {
            return response()->json([
                'success' => false,
                'message' => 'Semua consumables memiliki quantity 0. Tidak ada data yang dikirim ke SAP.'
            ], 400);
        }

        // URL API SAP
        $sapApiUrl = "http://erpqas-dp.dharmap.com:8001/sap/zapi/ZMM_GI_SCRAP?sap-client=300";

        try {
            // Membuat client Guzzle
            $client = new Client();

            // Mengirim request POST ke API SAP
            $response = $client->post($sapApiUrl, [
                'json' => $sapPayload,
                'auth' => ["dpm-itfc01", "Dharma48"],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            // Mengambil respons dari API SAP
            $responseBody = json_decode($response->getBody(), true);

            return response()->json([
                'success' => true,
                'data' => $responseBody,
            ]);

        } catch (\Exception $e) {
            // Menangani error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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