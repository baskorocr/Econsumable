<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use App\Models\MstrLineGroup;
use App\Models\MstrMaterial;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MstrMaterialImport;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $materials = MstrMaterial::where('Mt_desc', 'like', '%' . $search . '%')
            ->paginate(10);
        $lineGroups = MstrLineGroup::all();
        return view('admin.master.material.index', compact('materials', 'lineGroups', 'search'));
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
            'Mt_number' => 'required|string|max:255|unique:mstr_materials',
            'Mt_lgId' => 'required|string',
            'Mt_desc' => 'required|string',
        ]);

        MstrMaterial::create($request->all());

        return redirect()->route('Material.index');
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
        $material = MstrMaterial::findOrFail($id);
        $request->validate([
            'Mt_number' => 'required|string|max:255|unique:mstr_materials,Mt_number,' . $material->Mt_number . ',Mt_number',
            'Mt_lgId' => 'required|string',
            'Mt_desc' => 'required|string',
        ]);



        $material->update($request->all());

        return redirect()->route('Material.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {


        $material = MstrMaterial::findOrFail($id);
        $material->delete();
        return redirect()->route('Material.index');
    }
    public function downloadTemplate()
    {
        // Path to the template file in the public directory
        $filePath = public_path('templates/example.xlsx');

        // Check if the file exists
        if (!file_exists($filePath)) {
            abort(404, 'Template file not found.');
        }

        // Return the file as a download response
        return response()->download($filePath, 'example_template.xlsx');
    }


    public function uploadExcel(Request $request)
    {


        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls'
        ]);



        try {
            // Import data
            $import = new MstrMaterialImport;
            Excel::import($import, $request->file('excelFile'));

            // Get the status messages
            $statusMessages = $import->getStatusMessages();

            // Log the status messages
            Log::info('Import status: ' . json_encode($statusMessages));

            // Check if all rows were successfully imported
            $allSuccessful = !in_array(false, $statusMessages, true);


            if ($allSuccessful) {
                return back()->with('success', 'Data Excel berhasil diimpor.');
            } else {
                return back()->with('warning', 'Data Excel sebagian berhasil diimpor. Periksa log untuk detail.');
            }
        } catch (\Exception $e) {
            Log::error('Error during import: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}