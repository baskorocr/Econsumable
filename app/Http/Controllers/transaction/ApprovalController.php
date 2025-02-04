<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
use App\Models\OrderSegment;
use App\Models\SapFail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use GuzzleHttp\Client;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');


        if (auth()->user()->idRole == 2) {



            // $apprs = MstrAppr::with(['consumable.masterLineGroup', 'orderSegment.user'])->where(
            //     'status',
            //     1
            // )->where('NpkSect', auth()->user()->npk)->paginate(20);





            $apprs = OrderSegment::with(['mstrApprs.consumable.masterLineGroup'])
                ->whereHas('mstrApprs', function ($q) {
                    $q->where('NpkSect', auth()->user()->npk)->where('status', 1);
                })
                ->when($search, function ($query, $search) {
                    $query->where('noOrder', 'like', "%$search%");
                })
                ->paginate(20);




        } elseif (auth()->user()->idRole == '3') {

            $apprs = OrderSegment::with(['mstrApprs.consumable.masterLineGroup'])
                ->whereHas('mstrApprs', function ($q) {
                    $q->where('NpkDept', auth()->user()->npk)->where('status', 2);
                })
                ->when($search, function ($query, $search) {
                    $query->where('noOrder', 'like', "%$search%");
                })
                ->paginate(20);
            // $apprs = MstrAppr::with(['consumable.masterLineGroup', 'orderSegment.user'])->where(
            //     'status',
            //     2
            // )->where('NpkDept', auth()->user()->npk)->paginate(20);

        } elseif (auth()->user()->idRole == '4') {

            $apprs = OrderSegment::with(['mstrApprs.consumable.masterLineGroup'])
                ->whereHas('mstrApprs', function ($q) {
                    $q->where('NpkPj', auth()->user()->npk)->where('status', 3);
                })
                ->when($search, function ($query, $search) {
                    $query->where('noOrder', 'like', "%$search%");
                })
                ->paginate(20);
            // $apprs = MstrAppr::with(['consumable.masterLineGroup', 'orderSegment.user'])->where(
            //     'status',
            //     3
            // )->where('NpkPj', auth()->user()->npk)->paginate(20);

        } elseif (auth()->user()->idRole == '5') {


            // $apprs = OrderSegment::with(['mstrApprs.consumable.material.masterLineGroup'])
            //     ->whereHas('mstrApprs', function ($q) {
            //         $q->where('status', 4);
            //     })
            //     ->when($search, function ($query, $search) {
            //         $query->where('noOrder', 'like', "%$search%");
            //     })
            //     ->where('NpkUser', auth()->user()->npk)->paginate(20);



        }





        return view('transaction.approval', compact('apprs', 'search'));


    }

    public function indexStatus()
    {

        // $status = OrderSegment::with(['mstrApprs.sapFails', 'mstrApprs.consumable.masterLineGroup', 'user'])
        //     ->whereHas(
        //         'mstrApprs.consumable.masterLineGroup',
        //         function ($e) {
        //             $e->where('NpkPjStock', auth()->user()->npk);
        //         }
        //     )->whereHas(
        //         'mstrApprs',
        //         function ($e) {
        //             $e->where('status', 0);
        //         }
        //     )->whereHas('mstrApprs.sapFails', function ($e) {
        //         $e->where('Desc_message', 'FAILED');
        //     })
        //     ->get();

        $status = OrderSegment::with([
            'mstrApprs.sapFails' => function ($query) {
                $query->where('Desc_message', '!=', 'SUCCESS');
            },
            'mstrApprs.consumable.masterLineGroup',
            'user'
        ])->whereHas('mstrApprs.sapFails', function ($query) {
            $query->where('Desc_message', '!=', 'SUCCESS');
        })->whereHas('mstrApprs.consumable.masterLineGroup', function ($query) {
            $query->where('NpkPjStock', auth()->user()->npk);
        })->paginate(20);


        return view('transaction.sapStatus', compact('status'));
    }

    public function resend(Request $request)
    {
        $date = date('Y-m-d');

        $apprs = MstrAppr::with([
            'user',
            'consumable.masterLineGroup.group',
            'consumable.masterLineGroup.leader',
            'consumable.masterLineGroup.section',
            'consumable.masterLineGroup.lines',
            'consumable.masterLineGroup.pjStock',
            'consumable.masterLineGroup.plan',
            'consumable.masterLineGroup.costCenter',
            'sapFails' => function ($query) {
                $query->where('Desc_message', '!=', 'SUCCESS');
            },
            'orderSegment',

        ])->where('no_order', $request->no_order)->where('status', 0)->get();


        foreach ($apprs as $fail) {
            $message = $this->sapSend($fail, $fail->orderSegment);

            if ($message['lt_message'][0]['message_gi'] === 'SUCCESS') {
                $fail->update([
                    'status' => 4,
                    'token' => null,
                    'ApprPjStokDate' => $date

                ]);


                foreach ($fail->sapFails as $sapFail) {
                    $sapFail->update([
                        'matdoc_gi' => $message['lt_message'][0]['matdoc_gi'],
                        'Desc_message' => $message['lt_message'][0]['message_gi']
                    ]);
                }

            } else {
                $fail->update([

                    'ApprPjStokDate' => $date

                ]);
                foreach ($fail->sapFails as $sapFail) {
                    $sapFail->update([
                        'Desc_message' => $message['lt_message'][0]['message_gi']
                    ]);
                }
            }

        }
        Alert::success('Resend Success', 'Check Success Request on SSR, For Check Failed Request/Error on SAP Error');
        return redirect()->back();





    }


    public function acc($id)
    {

        $date = date('Y-m-d');
        $temp = 0;
        $message = [];


        try {
            $appr = MstrAppr::with([
                'user',
                'consumable.masterLineGroup.group',
                'consumable.masterLineGroup.leader',
                'consumable.masterLineGroup.section',
                'consumable.masterLineGroup.lines',
                'consumable.masterLineGroup.pjStock',
                'consumable.masterLineGroup.plan',
                'consumable.masterLineGroup.costCenter',
                'orderSegment',

            ])->where('no_order', $id)->get();






            foreach ($appr as $item) {
                $consumable = $item->consumable->masterLineGroup;

                if ($item->status == 1) {


                    $a = $item->update([
                        'status' => $item->status + 1,
                        'token' => Str::uuid()->toString(),
                        'ApprSectDate' => $date

                    ]);


                    if ($a === true && $temp == 0) {
                        $noHp = $item->consumable->masterLineGroup->leader->noHp ?? null;


                        if ($noHp !== null) {

                            SendWa($consumable->leader->noHp, $consumable->leader->name, $item->orderSegment->noOrder, $item->user->name, $item->token, $item->no_order);

                        }

                    }

                } elseif ($item->status == 2) {



                    $a = $item->update([
                        'status' => $item->status + 1,
                        'token' => Str::uuid()->toString(),
                        'ApprDeptDate' => $date

                    ]);


                    if ($a === true) {
                        $noHp = $item->consumable->masterLineGroup->pjStock->noHp ?? null;
                        if ($noHp !== null) {

                            SendWa($consumable->pjStock->noHp, $consumable->pjStock->name, $item->orderSegment->noOrder, $item->user->name, $item->token, $item->no_order);
                        }

                    }


                } elseif ($item->status == 3) {



                    $message = $this->sapSend($item, $item->orderSegment);



                    //          if ($responseBody['lt_message'][0]['message_gi'] === 'SUCCESS') {
                    //     return 'SUCCESS';
                    // } else {
                    //     return $responseBody['lt_message'][0]['message_gi'];
                    // }

                    if ($message['lt_message'][0]['message_gi'] === 'SUCCESS') {
                        $item->update([
                            'status' => $item->status + 1,
                            'token' => null,

                            'ApprPjStokDate' => $date

                        ]);
                        SapFail::create([
                            'idAppr' => $item->_id,
                            'matdoc_gi' => $message['lt_message'][0]['matdoc_gi'],
                            'Desc_message' => $message['lt_message'][0]['message_gi']
                        ]);

                    } else {
                        $item->update([
                            'status' => 0,
                            'token' => null,
                            'ApprPjStokDate' => $date

                        ]);
                        SapFail::create([
                            'idAppr' => $item->_id,
                            'Desc_message' => $message['lt_message'][0]['message_gi']
                        ]);

                    }
                }

            }

            Alert::success('Approve Success', 'You can give information about that to user request for check periodically');


        } catch (Exception $e) {
            dd($e);
            Alert::error('Approve failed', $e->getMessage());
            return redirect()->route('approvalConfirmation.index');

        }

        return redirect()->route('approvalConfirmation.index');
    }
    public function reject($id)
    {


        try {
            $appr = MstrAppr::where('no_order', $id)->get();

            foreach ($appr as $item) {
                $item->update([
                    'status' => 0,
                    'token' => null

                ]);
            }

            Alert::success('Reject Success', 'Request has been fully rejected');

        } catch (Exception $e) {

            Alert::error('Approve failed', $e->getMessage());
            return redirect()->route('approvalConfirmation.index');
        }



        return redirect()->route('approvalConfirmation.index')->with('success', 'Approval successfully.');
    }

    public function apprNon($id, $token)
    {

        $appr = MstrAppr::where('no_order', $id)->where('token', $token)->first();

        if ($appr == null) {
            abort(403, "token has expired");
        }
        return view('transaction.confimation', compact('appr'));

    }

    public function accNon(Request $request)
    {

        $token = $request->input('token');
        $no_order = $request->input('no_order');
        $date = date('Y-m-d');

        try {


            $item = MstrAppr::with([
                'user',
                'consumable.masterLineGroup.group',
                'consumable.masterLineGroup.leader',
                'consumable.masterLineGroup.section',
                'consumable.masterLineGroup.lines',
                'consumable.masterLineGroup.pjStock',
                'consumable.masterLineGroup.plan',
                'consumable.masterLineGroup.costCenter',
                'orderSegment',

            ])->where('no_order', $no_order)->where('token', $token)->get();


            foreach ($item as $appr) {
                if ($appr->status == 1) {
                    $a = $appr->update([
                        'status' => $appr->status + 1,
                        'token' => Str::uuid()->toString(),
                        'ApprSectDate' => $date

                    ]);


                    if ($a === true || $appr->consumable->masterLineGroup->leader->noHp !== null) {
                        SendWa($appr->consumable->masterLineGroup->leader->noHp, $appr->consumable->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token, $appr->no_order);
                    }

                } elseif ($appr->status == 2) {
                    $a = $appr->update([
                        'status' => $appr->status + 1,
                        'token' => Str::uuid()->toString(),
                        'ApprDeptDate' => $date

                    ]);

                    if ($a === true || $appr->consumable->masterLineGroup->leader->noHp !== null) {
                        SendWa($appr->consumable->masterLineGroup->pjStock->noHp, $appr->consumable->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token, $appr->no_order);
                    }

                } elseif ($appr->status == 3) {
                    $a = $appr->update([
                        'status' => $appr->status,
                        'token' => null,
                        'ApprPjStokDate' => $date

                    ]);
                    $this->sapSend($appr, $appr->orderSegment);





                }
            }



            Alert::success('Approve Success', 'Thanks for your have been Approved');

        } catch (Exception $e) {
            Alert::error('Approve failed', $e->getMessage());
            return redirect()->route('home');

        }


        return redirect()->route('home');
    }

    public function rejectNon(Request $request)
    {
        $token = $request->input('token');
        $no_order = $request->input('no_order');

        try {

            $item = MstrAppr::where('no_order', $no_order)->where('token', $token)->get();



            foreach ($item as $appr) {
                $appr->update([
                    'status' => 0,
                    'token' => null

                ]);
            }

            Alert::success('Reject Success', 'Request has been fully rejected');

        } catch (Exception $e) {
            Alert::error('Reject failed', $e->getMessage());
            return redirect()->route('home');
        }


        return redirect()->route('home');

    }


    public function sapSend($consumable, $orderSegment)
    {


        //->where('status', 3)
        // foreach ($approvals as $approval) {
        //     $approval->status = 3; // Set the status to 3
        //     $approval->save(); // Save each individual model
        // }



        // Menyiapkan payload untuk API SAP
        $sapPayload = [
            "cons" => "X",
            "LT_INPUT" => []
        ];







        $sapPayload['LT_INPUT'][] = [
            "MATERIAL" => $consumable->consumable->Cb_number,
            "PLANT_ASAL" => $consumable->consumable->masterLineGroup->plan->Pl_code,
            "SLOC_ASAL" => $consumable->consumable->masterLineGroup->Lg_slocId,
            "QUANTITY" => $consumable->jumlah,
            "SATUAN" => $consumable->consumable->Cb_type, // Ubah ke satuan yang sesuai jika perlu
            "COST_CENTER" => $consumable->consumable->masterLineGroup->costCenter->Cs_code,
            "ORDER_ORG" => $orderSegment->noOrder,
            "INTERNAL_ORDER" => $consumable->consumable->Cb_IO,
            "REASON" => $consumable->lineFrom,

        ];










        // Filter untuk menghapus elemen dengan QUANTITY = "0"
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

            return $responseBody;

        } catch (\Exception $e) {
            // Menangani error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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