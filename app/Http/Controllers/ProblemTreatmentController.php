<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProblemTreatment;

class ProblemTreatmentController extends Controller
{

    public function index()
    {
        if (request()->wantsJson()) {
            return ProblemTreatment::with(['problem', 'treatment', 'tooth', 'visit'])->get();
        }
        
        $problemTreatments = ProblemTreatment::all();
        return inertia('Management/ProblemTreatment', compact('problemTreatments'));
    }

    public function create()
    {
        return inertia('Management/ProblemTreatment/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required',
            'problem_catalog_id' => 'required',
            'treatment_id' => 'required',
            'tooth_id' => 'required|integer|between:11,48',
            'notes' => 'nullable|string',
        ]);

        $problemTreatment = ProblemTreatment::create([
            'visit_id' => $request->visit_id,
            'problem_catalog_id' => $request->problem_catalog_id,
            'treatment_id' => $request->treatment_id,
            'tooth_id' => $request->tooth_id,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        if ($request->wantsJson()) {
            return response()->json($problemTreatment->load(['problem', 'treatment', 'tooth', 'visit']));
        }

        return redirect()->route('problemTreatment');
    }

    public function show(ProblemTreatment $problemTreatment)
    {
        return inertia('Management/ProblemTreatment/Show', compact('problemTreatment'));
    }

    public function edit(ProblemTreatment $problemTreatment)
    {
        return inertia('Management/ProblemTreatment/Edit', compact('problemTreatment'));
    }

    public function update(Request $request, ProblemTreatment $problemTreatment)
    {
        $request->validate([
            'visit_id' => 'required',
            'problem_catalog_id' => 'required',
            'treatment_id' => 'required',
        ]);

        $problemTreatment->update([
            'visit_id' => $request->visit_id,
            'problem_catalog_id' => $request->problem_catalog_id,
            'treatment_id' => $request->treatment_id,
        ]);

        return redirect()->route('problemTreatments.index');
    }

    public function destroy(ProblemTreatment $problemTreatment)
    {
        $problemTreatment->delete();
        return redirect()->route('problemTreatments.index');
    }
}
