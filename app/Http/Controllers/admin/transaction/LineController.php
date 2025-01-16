<?php

namespace App\Http\Controllers\admin\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLineGroup;
use App\Models\MstrMaterial;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        return view('transaction.material', compact('materials'));
    }


    public function indexConsumable($lines, $material)
    {
        dd($lines, $material);
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