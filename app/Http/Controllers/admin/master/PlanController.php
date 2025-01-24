<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrPlan;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;


class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $plan = MstrPlan::where('Pl_name', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.master.planMaster.index', compact('plan', 'search'));

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
            'PlanName' => 'required|array',
            'PlanName.*' => 'required|string|max:255',
            'PlanCode' => 'required|array',
            'PlanCode.*' => 'required|integer|digits_between:1,6',
        ]);



        // Loop through each name and code in the arrays and create a new Plan
        try {
            foreach ($request->PlanName as $index => $name) {
                MstrPlan::create([
                    'Pl_name' => $name,
                    'Pl_code' => $request->PlanCode[$index],
                ]);

            }
            Alert::success('Add Success', 'Data Plant added successfully');
        } catch (Exception $e) {
            Alert::error('Add failed', $e->getMessage());
            return redirect()->route('Plan.index');

        }

        return redirect()->route('Plan.index');
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
            'Pl_name' => 'required|string|max:255',

        ]);

        try {
            MstrPlan::findOrFail($id)->update($request->all());
            Alert::success('Update Success', 'Data Plant has been successfully updated');

        } catch (Exception $e) {
            Alert::error('update failed', $e->getMessage());
            return redirect()->route('Plan.index');

        }

        return redirect()->route('Plan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $plan = MstrPlan::findOrFail($id);
            Alert::success('Delete ' . $plan->Pl_name, 'Data Plant has been deleted successfully.');
            $plan->delete();

        } catch (Exception $e) {
            Alert::error('delete failed', $e->getMessage());
            return redirect()->route('Plan.index');
        }

        return redirect()->route('Plan.index');
    }
}