<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
use App\Models\MstrMaterial;
use App\Models\orderSegment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $generate = $this->generateCustomID($segment->masterLineGroup->group->Gr_segment);




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
                $requestId->load('orderSegment');

                if ($segment->masterLineGroup->leader->noHp !== null) {

                    $this->sendWa($segment->masterLineGroup->leader->noHp, $segment->masterLineGroup->leader->name, $requestId->orderSegment->noOrder)->get();
                }




            } catch (\Exception $e) {
                dd($e);
            }


        }



        return redirect()->route('listLine');



    }

    public function generateCustomID($segment)
    {
        $date = date('dmy');

        // Hitung jumlah order yang sudah ada pada tanggal yang sama
        $RCount = (orderSegment::where('noOrder', 'like', '%' . $date . '%')->count()) + 1;

        // Format nomor urut dengan 4 digit (misalnya: 0001, 0002, 0003)
        $formattedSequence = str_pad($RCount, 4, '0', STR_PAD_LEFT); // Menjaga nomor urut selalu 4 digit

        // Angka acak di akhir (misalnya antara 1-9)


        // Gabungkan menjadi kode akhir
        $code = "EC" . $date . $formattedSequence . '-' . $segment;
        $code = orderSegment::create([

            'noOrder' => $code
        ]);


        return $code->_id;

    }

    public function sendWa($nomer, $name, $requestId)
    {
        $by = auth()->user()->name;
        $date = date('l, d F Y');
        header('Access-Control-Allow-Origin: *');

        header('Access-Control-Allow-Methods: GET, POST');

        $message = "Hi {$name}, kamu baru saja menerima notifikasi approval request dari sistem Econsumable dengan detail:

- Request By : {$by}
- Request Date: {$date}
- Request ID: {$requestId}


Anda dapat dengan segera mengkonfirmasi pada link dibawah ini:

link ApproveRequest: dasdsadsadsa
link RejectRequest: dasdsadsadsa

Best regards,
IT Development Dharma Polimetal
                    ";

        header("Access-Control-Allow-Headers: X-Requested-With");
        $curl = curl_init();
        $token = "66g9FzV9n6sGzMUyEP@H";
        $target = $nomer;
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token" //change TOKEN to your actual token
                ),
            )
        );

        $response = curl_exec($curl);


        curl_close($curl);
        dd($response);
        echo $response;
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