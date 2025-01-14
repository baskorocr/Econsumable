<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLineGroup;
use App\Models\MstrPlan;
use App\Models\MstrCostCenter;
use App\Models\MstrLine;
use App\Models\MstrGroup;
use App\Models\MstrSloc;
use App\Models\User;

class LineGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lineGroups = MstrLineGroup::with(['plan', 'costCenter', 'line', 'group', 'sloc', 'leader', 'section', 'pjStock'])->paginate(20);


        return view('admin.master.line_groups.index', compact('lineGroups'));
    }

    public function create()
    {
        $plans = MstrPlan::all();
        $costCenters = MstrCostCenter::all();
        $lines = MstrLine::all();
        $groups = MstrGroup::all();
        $slocs = MstrSloc::all();
        $usersSects = User::where('idRole', 2)->get();
        $usersDepts = User::where('idRole', 3)->get();
        $pjs = User::where('idRole', 4)->get();
        return view('admin.master.line_groups.create', compact('plans', 'costCenters', 'lines', 'groups', 'slocs', 'usersSects', 'usersDepts', 'pjs'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'Lg_code' => 'required|string|max:255|unique:mstr_line_groups',
            'Lg_plId' => 'required|integer',
            'Lg_csId' => 'required|integer',
            'Lg_lineId' => 'required|string',
            'Lg_groupId' => 'required|integer',
            'Lg_slocId' => 'required|integer',
            'NpkLeader' => 'required|string|max:255',
            'NpkSection' => 'required|string|max:255',
            'NpkPjStock' => 'required|string|max:255',
        ]);

        MstrLineGroup::create([
            'Lg_code' => $request->Lg_code,
            'Lg_plId' => $request->Lg_plId,
            'Lg_csId' => $request->Lg_csId,
            'Lg_lineId' => $request->Lg_lineId,
            'Lg_groupId' => $request->Lg_groupId,
            'Lg_slocId' => $request->Lg_slocId,
            'NpkLeader' => $request->NpkLeader,
            'NpkSection' => $request->NpkSection,
            'NpkPjStock' => $request->NpkPjStock,
        ]);

        return redirect()->route('LineGroup.index')->with('success', 'Line Group created successfully.');
    }

    public function edit($id)
    {
        $lineGroup = MstrLineGroup::with(['plan', 'costCenter', 'line', 'group', 'sloc', 'leader', 'section', 'pjStock'])->findOrFail($id);

        $plans = MstrPlan::all();
        $costCenters = MstrCostCenter::all();
        $lines = MstrLine::all();
        $usersSects = User::where('idRole', 2)->get();
        $usersDepts = User::where('idRole', 3)->get();
        $pjs = User::where('idRole', 4)->get();
        $groups = MstrGroup::all();
        $slocs = MstrSloc::all();

        return view('admin.master.line_groups.edit', compact('lineGroup', 'usersSects', 'usersDepts', 'pjs', 'plans', 'costCenters', 'lines', 'groups', 'slocs'));
    }

    public function update(Request $request, $id)
    {


        $request->validate([
            'Lg_code' => 'required|integer ',
            'Lg_plId' => 'required|integer',
            'Lg_csId' => 'required|integer',
            'Lg_lineId' => 'required|string',
            'Lg_groupId' => 'required|integer',
            'Lg_slocId' => 'required|integer',
            "NpkLeader" => "required|string",
            "NpkSection" => "required|string",
            "NpkPjStock" => "required|string"
        ]);



        $lineGroup = MstrLineGroup::findOrFail($id);



        $lineGroup->Lg_plId = $request->Lg_plId;
        $lineGroup->Lg_csId = $request->Lg_csId;
        $lineGroup->Lg_lineId = $request->Lg_lineId;
        $lineGroup->Lg_groupId = $request->Lg_groupId;
        $lineGroup->Lg_slocId = $request->Lg_slocId;
        $lineGroup->NpkLeader = $request->NpkLeader;
        $lineGroup->NpkSection = $request->NpkSection;
        $lineGroup->NpkPjStock = $request->NpkPjStock;


        $lineGroup->save();

        return redirect()->route('LineGroup.index')->with('success', 'Line Group updated successfully.');
    }

    public function destroy($id)
    {
        $lineGroup = MstrLineGroup::findOrFail($id);
        $lineGroup->delete();

        return redirect()->route('LineGroup.index')->with('success', 'Line Group deleted successfully.');
    }
}