<?php

namespace App\Http\Controllers\admin\master;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLine;

class MasterLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $lines = MstrLine::where('Ln_name', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.master.lineMaster.index', compact('lines', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input to ensure it's an array of line names
        $request->validate([
            'nameLine' => 'required|array',
            'nameLine.*' => 'required|string|max:255',
        ]);

        // Loop through each name in the 'nameLine' array and create a new Line
        foreach ($request->nameLine as $name) {
            MstrLine::create(['Ln_name' => $name]);
        }

        return redirect()->route('MasterLine.index');
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MstrLine $line)
    {



        $request->validate([
            'Ln_name' => 'required|string|max:255',
        ]);

        $data = MstrLine::findOrFail($request->line_id);



        $data->Ln_name = $request->Ln_name;
        $data->save();


        return redirect()->route('MasterLine.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $line = MstrLine::findOrFail($id);
        $line->delete();
        return redirect()->route('MasterLine.index');

    }
}