<?php

namespace App\Http\Controllers\admin\master;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrLine;
use App\Models\MstrLineGroup;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;


class MasterLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $lines = MstrLine::with('lineGroup')->where('Ln_name', 'like', '%' . $search . '%')
            ->paginate(10);
        $lgs = MstrLineGroup::all();


        return view('admin.master.lineMaster.index', compact('lines', 'lgs', 'search'));
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
        dd($request->all());
        // Validate the input to ensure it's an array of line names
        $request->validate([
            'nameLine' => 'required|array',
            'nameLine.*' => 'required|string|max:255',

            'Lg_id' => 'required|array',
            'Lg_id.*' => 'required|string|max:255',
        ]);

        // Loop through each name in the 'nameLine' array and create a new Line
        try {
            foreach ($request->nameLine as $index => $name) {
                MstrLine::create([
                    'Ln_name' => $name,
                    'Ln_lgId' => $request->Lg_id[$index] ?? null, // Mengambil Lg_id yang sesuai berdasarkan index
                ]);
            }
            Alert::success('Add Success', 'Data Line added successfully');

        } catch (Exception $e) {
            Alert::error('Add failed', $e->getMessage());
            return redirect()->route('MasterLine.index');
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

        try {
            $data = MstrLine::findOrFail($request->line_id);
            $data->Ln_name = $request->Ln_name;
            $data->save();
            Alert::success('Update Success', 'Data Line has been successfully updated');

        } catch (Exception $e) {
            Alert::error('update failed', $e->getMessage());
            return redirect()->route('MasterLine.index');

        }


        return redirect()->route('MasterLine.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $line = MstrLine::findOrFail($id);
            Alert::success('Delete ' . $line->Ln_name, 'Data Line has been deleted successfully.');
            $line->delete();

        } catch (Exception $e) {
        }
        return redirect()->route('MasterLine.index');

    }

    // public function uploadExcel(Request $request)
    // {
    //     $request->validate([
    //         'excelFile' => 'required|file|mimes:xlsx,xls|max:2048', // Validate file type and size
    //     ]);



    //     if ($request->hasFile('excelFile')) {
    //         $file = $request->file('excelFile');
    //         $fileName = time() . '_' . $file->getClientOriginalName();
    //         $uploadPath = storage_path('app/uploads/excel'); // Define upload directory

    //         // Move the uploaded file
    //         $file->move($uploadPath, $fileName);

    //         // Process the uploaded file (Optional: Add processing logic here)

    //         return redirect()->back()->with('success', 'File uploaded successfully!');
    //     }

    //     return redirect()->back()->with('error', 'Failed to upload file.');
    // }
}