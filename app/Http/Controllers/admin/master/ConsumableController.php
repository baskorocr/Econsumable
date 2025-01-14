<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrMaterial;
use App\Models\MstrConsumable;

class ConsumableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumables = MstrConsumable::with('material')->paginate(20);


        $materials = MstrMaterial::all();
        return view('admin.master.consumables.index', compact('consumables', 'materials'));
    }

    public function create()
    {
        $materials = MstrMaterial::all();
        return view('admin.master.consumables.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Cb_number' => 'required|string|max:255|unique:mstr_consumables',
            'Cb_mtId' => 'required|string',
            'Cb_desc' => 'required|string',
        ]);

        MstrConsumable::create($request->all());

        return redirect()->route('Consumable.index')->with('success', 'Consumable created successfully.');
    }

    public function show(MstrConsumable $consumable)
    {
        return view('admin.master.consumables.show', compact('consumable'));
    }

    public function edit(MstrConsumable $consumable)
    {
        $materials = MstrMaterial::all();
        return view('admin.master.consumables.edit', compact('consumable', 'materials'));
    }

    public function update(Request $request, $id)
    {

        $consumable = MstrConsumable::findOrFail($id);
        $request->validate([
            'Cb_number' => 'required|string|max:255|unique:mstr_consumables,Cb_number,' . $consumable->Cb_number . ',Cb_number',
            'Cb_mtId' => 'required|string',
            'Cb_desc' => 'required|string',
        ]);

        $consumable->update($request->all());

        return redirect()->route('Consumable.index')->with('success', 'Consumable updated successfully.');
    }

    public function destroy($id)
    {
        $consumable = MstrConsumable::findOrFail($id);

        $consumable->delete();

        return redirect()->route('Consumable.index')->with('success', 'Consumable deleted successfully.');
    }
}