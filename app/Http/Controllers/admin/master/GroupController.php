<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrGroup;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $groups = MstrGroup::where('Gr_name', 'like', '%' . $search . '%')
            ->orWhere('Gr_segment', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.master.group.index', compact('groups', 'search'));
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
            'Gr_name' => 'required|array',
            'Gr_name.*' => 'required|string|max:255',
            'Gr_segment' => 'nullable|array',
            'Gr_segment.*' => 'required|string|max:255',
        ]);

        $data = $request->all();

        foreach ($data['Gr_name'] as $index => $name) {
            MstrGroup::create([
                'Gr_name' => $name,
                'Gr_segment' => $data['Gr_segment'][$index] ?? null,
            ]);
        }

        return redirect()->route('Group.index');


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
            'Gr_name' => 'required|string|max:255',
            'Gr_segment' => 'nullable|string|max:255',
        ]);

        $gr = MstrGroup::findOrFail($id);
        $gr->Gr_name = $request->Gr_name;
        $gr->Gr_segment = $request->Gr_segment;
        $gr->save();

        return redirect()->route('Group.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gr = MstrGroup::findOrFail($id);
        $gr->delete();
        return redirect()->route('Group.index');
    }
}