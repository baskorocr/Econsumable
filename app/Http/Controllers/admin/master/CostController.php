<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrCostCenter;
class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $costs = MstrCostCenter::where('Cs_code', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.master.cc.index', compact('costs', 'search'));
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
        $request->validate([
            'Cs_code' => 'required|array',
            'Cs_code.*' => 'required|integer|digits_between:1,5',
            'Cs_name' => 'required|array',
            'Cs_name.*' => 'required|string|max:255',
        ]);

        $data = $request->all();

        foreach ($data['Cs_code'] as $index => $code) {
            MstrCostCenter::create([
                'Cs_code' => $code,
                'Cs_name' => $data['Cs_name'][$index],
            ]);
        }

        return redirect()->route('Cost.index');


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

        $request->validate([
            'Cs_name' => 'required|string|max:255',
        ]);

        $cs = MstrCostCenter::findOrFail($request->Cs_code);
        $cs->Cs_name = $request->Cs_name;
        $cs->save();

        return redirect()->route('Cost.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cs = MstrCostCenter::findOrFail($id);
        $cs->delete();
        return redirect()->route('Cost.index');
    }
}