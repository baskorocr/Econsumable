<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use App\Models\MstrGroup;
use App\Models\MstrLineGroup;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //


    public function index()
    {
        $segments = MstrGroup::all();

        return view('transaction.report', compact('segments'));
    }

    public function getLinesBySegment(Request $request)
    {
        $segmentId = $request->query('segment_id');

        // Ambil data Line berdasarkan segment_id
        $lines = MstrLineGroup::with(['line'])->where('Lg_groupId', $segmentId)->get();
        $lines = $lines->map(function ($group) {
            return $group->line;
        });

        return response()->json($lines);

    }
}