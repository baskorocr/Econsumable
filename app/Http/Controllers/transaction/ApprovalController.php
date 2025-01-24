<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
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


        if (auth()->user()->idRole == '2') {

            $apprs = MstrAppr::with(['orderSegment', 'consumable', 'user'])
                ->when($search, function ($query, $search) {
                    $query->whereHas('orderSegment', function ($q) use ($search) {
                        $q->where('noOrder', 'like', "%$search%");
                    });
                })
                ->where('status', 1)->paginate(20);

        } elseif (auth()->user()->idRole == '3') {
            $apprs = MstrAppr::with(['orderSegment', 'consumable', 'user'])
                ->when($search, function ($query, $search) {
                    $query->whereHas('orderSegment', function ($q) use ($search) {
                        $q->where('noOrder', 'like', "%$search%");
                    });
                })
                ->where('status', 2)->paginate(20);

        } elseif (auth()->user()->idRole == '4') {
            $apprs = MstrAppr::with(['orderSegment', 'consumable', 'user'])
                ->when($search, function ($query, $search) {
                    $query->whereHas('orderSegment', function ($q) use ($search) {
                        $q->where('noOrder', 'like', "%$search%");
                    });
                })
                ->where('status', 3)->paginate(20);

        } elseif (auth()->user()->idRole == '5') {
            $apprs = MstrAppr::with(['orderSegment', 'consumable', 'user'])
                ->when($search, function ($query, $search) {
                    $query->whereHas('orderSegment', function ($q) use ($search) {
                        $q->where('noOrder', 'like', "%$search%");
                    });
                })
                ->where('status', 4)->paginate(20);

        } else {
            $apprs = MstrAppr::with(['orderSegment', 'consumable', 'user'])
                ->when($search, function ($query, $search) {
                    $query->whereHas('orderSegment', function ($q) use ($search) {
                        $q->where('noOrder', 'like', "%$search%");
                    });
                })
                ->paginate(20);

        }




        return view('transaction.approval', compact('apprs', 'search'));


    }


    public function acc($id)
    {

        $date = date('Y-m-d');

        try {
            $appr = MstrAppr::with(['user', 'consumable.material.masterLineGroup.group', 'consumable.material.masterLineGroup.leader', 'consumable.material.masterLineGroup.section', 'consumable.material.masterLineGroup.pjStock'])->findOrFail($id);

            if ($appr->status == 1) {

                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => Str::uuid()->toString(),
                    'ApprSectDate' => $date

                ]);
                if ($a === true || $appr->consumable->material->masterLineGroup->leader->noHp !== null) {
                    sendWa($appr->consumable->material->masterLineGroup->leader->noHp, $appr->consumable->material->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token);
                }

            } elseif ($appr->status == 2) {

                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => Str::uuid()->toString(),
                    'ApprDeptDate' => $date

                ]);

                if ($a === true || $appr->consumable->material->masterLineGroup->leader->noHp !== null) {
                    sendWa($appr->consumable->material->masterLineGroup->pjStock->noHp, $appr->consumable->material->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token);
                }


            } elseif ($appr->status == 3) {


                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => null,
                    'ApprPjStokDate' => $date

                ]);
                $this->sapSend($appr->no_order);


            }
            Alert::success('Approve Success', 'Thanks for your have been Approved');

        } catch (Exception $e) {
            dd($e);
            Alert::error('Approve failed', $e->getMessage());
            return redirect()->route('approvalConfirmation.index');

        }

        return redirect()->route('approvalConfirmation.index');
    }
    public function reject($id)
    {
        $appr = MstrAppr::findOrFail($id);
        try {
            $appr->update([
                'status' => 0,
                'token' => null

            ]);
            Alert::success('Reject Success', 'Request has been fully rejected');

        } catch (Exception $e) {

            Alert::error('Approve failed', $e->getMessage());
            return redirect()->route('approvalConfirmation.index');
        }



        return redirect()->route('approvalConfirmation.index')->with('success', 'Approval successfully.');
    }

    public function apprNon($id)
    {
        $appr = MstrAppr::where('token', $id)->first();
        $appr = $appr->token;
        return view('transaction.confimation', compact('appr'));

    }

    public function accNon(Request $request)
    {
        $token = $request->input('token');
        $date = date('Y-m-d');

        try {
            $appr = MstrAppr::where('token', $token)->first();


            if ($appr->status == 1) {
                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => Str::uuid()->toString(),
                    'ApprSectDate' => $date

                ]);


                if ($a === true || $appr->consumable->material->masterLineGroup->leader->noHp !== null) {
                    sendWa($appr->consumable->material->masterLineGroup->leader->noHp, $appr->consumable->material->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token);
                }

            } elseif ($appr->status == 2) {
                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => Str::uuid()->toString(),
                    'ApprDeptDate' => $date

                ]);

                if ($a === true || $appr->consumable->material->masterLineGroup->leader->noHp !== null) {
                    sendWa($appr->consumable->material->masterLineGroup->pjStock->noHp, $appr->consumable->material->masterLineGroup->leader->name, $appr->orderSegment->noOrder, $appr->user->name, $appr->token);
                }

            } elseif ($appr->status == 3) {
                $a = $appr->update([
                    'status' => $appr->status + 1,
                    'token' => null,
                    'ApprPjStokDate' => $date

                ]);



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
        $appr = MstrAppr::where('token', $token)->first();
        try {
            $appr->update([
                'status' => 0,
                'token' => null

            ]);
            Alert::success('Reject Success', 'Request has been fully rejected');

        } catch (Exception $e) {
            Alert::error('Reject failed', $e->getMessage());
            return redirect()->route('home');
        }


        return redirect()->route('home');

    }


    public function sapSend($noOrder)
    {
        $approvals = MstrAppr::with([
            'orderSegment',
            'consumable.material.masterLineGroup' => function ($query) {
                $query->with(['plan', 'costCenter']);
            }
        ])->where('status', 4)
            ->where('no_order', $noOrder)
            ->get();



        // Menyiapkan payload untuk API SAP
        $sapPayload = [
            "cons" => "X",
            "LT_INPUT" => []
        ];



        foreach ($approvals as $approval) {
            $sapPayload['LT_INPUT'][] = [
                "MATERIAL" => $approval->consumable->material->Mt_number,
                "PLANT_ASAL" => $approval->consumable->material->masterLineGroup->plan->Pl_code,
                "SLOC_ASAL" => $approval->consumable->material->masterLineGroup->Lg_slocId,
                "QUANTITY" => $approval->jumlah,
                "SATUAN" => "PCE", // Ubah ke satuan yang sesuai jika perlu
                "COST_CENTER" => $approval->consumable->material->masterLineGroup->costCenter->Cs_code,
                "ORDER_ORG" => $approval->orderSegment->noOrder

            ];
        }

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

            dd($responseBody);
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