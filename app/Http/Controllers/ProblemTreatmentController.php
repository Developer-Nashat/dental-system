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
        // dd($request);

        $validatedData = $request->validate([
            'visit_id' => 'required',
            'problem_id' => 'required',
            'treatment_id' => 'required',
            'tooth_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        // dd($validatedData);

        $problemTreatment = ProblemTreatment::create($validatedData);

        // if ($request->wantsJson()) {
        //     return response()->json($problemTreatment->load(['problem', 'treatment', 'tooth', 'visit']));
        // }
        return to_route('PatientVisit', $validatedData['visit_id'])->with('message', 'تم الإضافة بنجاح.');
        // return redirect()->route('PatientVisit', $validatedData['visit_id'])->with('message', 'تم الإضافة بنجاح.');
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
        $validatedData = $request->validate([
            'visit_id' => 'required',
            'problem_id' => 'required',
            'treatment_id' => 'required',
            'tooth_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $problemTreatment->update($validatedData);

        return redirect()->route('PatientVisit', $validatedData['visit_id'])->with('message', 'تم التعديل بنجاح.');
    }

    public function destroy(ProblemTreatment $problemTreatment)
    {
        $problemTreatment->delete();
        return redirect()->route('PatientVisit', $problemTreatment->visit_id)->with('message', 'تم الحذف بنجاح.');
    }
}
