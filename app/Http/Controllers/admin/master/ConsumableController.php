<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\MstrMaterial;
use App\Models\MstrConsumable;
use App\Models\MstrLineGroup;
use RealRashid\SweetAlert\Facades\Alert;


class ConsumableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search', '');

        $consumables = MstrConsumable::with('masterLineGroup')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('Cb_desc', 'like', '%' . $search . '%')
                    ->orWhere('Cb_number', 'like', '%' . $search . '%');
            })
            ->paginate(20);


        $lgs = MstrLineGroup::all();


        return view('admin.master.consumables.index', compact('consumables', 'lgs', 'search', ));
    }

    public function create()
    {
        $materials = MstrMaterial::all();
        return view('admin.master.consumables.create', compact('materials'));
    }

    public function store(Request $request)
    {



        $request->validate([
            'Cb_number' => 'required|string|max:255|',
            'Cb_type' => 'required|string|max:255|',
            'Cb_IO' => 'required|string|max:255|',
            'Cb_desc' => 'required|string',
            'Cb_lgId' => 'required|string',
        ]);



        try {
            MstrConsumable::create($request->all());
            Alert::success('Add Success', 'Data Consumable added successfully');


        } catch (Exception $e) {
            Alert::error('Add failed', $e->getMessage());
            return redirect()->route('Consumable.index');

        }



        return redirect()->route('Consumable.index');
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

        try {

            $consumable = MstrConsumable::findOrFail($id);
            $request->validate([
                'Cb_number' => 'required|string|max:255|unique:mstr_consumables,Cb_number,' . $consumable->Cb_number . ',Cb_number',
                'Cb_mtId' => 'required|string',
                'Cb_desc' => 'required|string',
            ]);


            $consumable->update($request->all());
            Alert::success('Update Success', 'Data Consumable has been successfully updated');


        } catch (Exception $e) {

            Alert::error('update failed', $e->getMessage());
            return redirect()->route('Consumable.index');

        }

        return redirect()->route('Consumable.index')->with('success', 'Consumable updated successfully.');
    }

    public function destroy($id)
    {

        try {
            $consumable = MstrConsumable::findOrFail($id);
            Alert::success('Delete ' . $consumable->Cb_desc, 'Data Consumable has been deleted successfully.');
            $consumable->delete();

        } catch (Exception $e) {
            Alert::error('delete failed', $e->getMessage());
            return redirect()->route('Consumable.index');
        }

        return redirect()->route('Consumable.index')->with('success', 'Consumable deleted successfully.');
    }
}