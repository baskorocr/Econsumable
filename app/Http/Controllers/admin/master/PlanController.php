<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MstrPlan;

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
        foreach ($request->PlanName as $index => $name) {
            $p = MstrPlan::create([
                'Pl_name' => $name,
                'Pl_code' => $request->PlanCode[$index],
            ]);

        }

        return redirect()->route('Plan.index')->with('success', 'Plans added successfully.');
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

        MstrPlan::findOrFail($id)->update($request->all());

        return redirect()->route('Plan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MstrPlan::findOrFail($id)->delete();

        return redirect()->route('Plan.index');
    }
}