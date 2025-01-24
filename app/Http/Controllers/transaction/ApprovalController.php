<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrAppr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $appr = MstrAppr::with(['user', 'consumable.material.masterLineGroup.group', 'consumable.material.masterLineGroup.leader', 'consumable.material.masterLineGroup.section', 'consumable.material.masterLineGroup.pjStock'])->findOrFail($id);
        $date = date('Y-m-d');

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
        return redirect()->route('approvalConfirmation.index')->with('success', 'Approval successfully.');
    }
    public function reject($id)
    {
        $appr = MstrAppr::findOrFail($id);

        $p = $appr->update([
            'status' => 0,
            'token' => null

        ]);
        dd($p);

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
        $appr = MstrAppr::where('token', $token)->first();

        $date = date('Y-m-d');


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

        return redirect()->route('home');





    }

    public function rejectNon($id)
    {
        return view('transaction.confimation');

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