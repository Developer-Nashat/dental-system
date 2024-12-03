<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private $customMessages =
    [
        'name.required' => 'يجب ان تدخل اسم المريض',
        'name.unique' => 'اسم المريض هذا موجود مسبقا',
        'identification_no.unique' => 'رقم البطاقة مسجل مسبقا',
    ];

    public function index()
    {
        try {
            $patients = Patient::all();
            // dd($patients);
            return inertia('Management/Patient', compact('patients'));
        } catch (\Exception $e) {
            return inertia('Management/Patient', [
                'patients' => [],
                'error' => 'فشل استرجاع البيانات',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:patients,name',
            'gender' => 'required|in:M,F',
            'age' => 'nullable|integer',
            'identification_no' => 'nullable|unique:patients,identification_no',
            'address' => 'nullable|string',
            'phone_1' => 'nullable|string',
            'phone_2' => 'nullable|string',
            'note' => 'nullable|string',
            'account_no' => 'nullable|string',
        ], $this->customMessages);

        try {
            Patient::create($validated);

            return redirect()->route('patients')->with('message', 'تم إضافة المريض بنجاح');
        } catch (\Exception $e) {
        }
    }

    public function show(Patient $patient)
    {
        return inertia('Management/PatientShow', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return inertia('Management/PatientEdit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        try {
            $fields = $request->validate([
                'name' => 'required',
                'gender' => 'required|in:M,F',
                'age' => 'nullable|integer',
                'identification_no' => 'nullable|unique:patients,identification_no,' . $patient->id,
                'address' => 'nullable|string',
                'phone_1' => 'nullable|string',
                'phone_2' => 'nullable|string',
                'note' => 'nullable|string',
                'account_no' => 'nullable|string',
            ], $this->customMessages);

            $patient->update($fields);

            return redirect()->route('patients')->with('message', 'تم تعديل بيانات المريض بنجاح.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'فشل في تعديل بيانات المريض.',
            ]);
        }
    }

    public function destroy(Patient $patient)
    {
        if (!$patient) {
            return back()->withErrors([
                'error' => 'فشل في حذف المريض.',
            ]);
        }

        try {
            $patient->delete();

            return redirect()->route('patients')->with('message', 'تم حذف المريض بنجاح.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'فشل في حذف المريض.',
            ]);
        }
    }
}
