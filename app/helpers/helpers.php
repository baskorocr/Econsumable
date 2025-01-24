<?php

use App\Models\MstrAppr;
use App\Models\orderSegment;
use App\Models\User;


function sendWa($nomer, $name, $requestId, $pembuat, $token)
{
    $approveLink = config('app.url') . ":8000/appr/{$token}";
    $date = date('l, d F Y');
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST');

    $message = "Hi {$name}, kamu baru saja menerima notifikasi approval request dari sistem Econsumable dengan detail:

- Request By : {$pembuat}
- Request Date: {$date}
- Request ID: {$requestId}


Anda dapat dengan segera mengkonfirmasi pada link dibawah ini:

link ApproveRequest:  {$approveLink}
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


}

function generateCustomID($segment)
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



?>