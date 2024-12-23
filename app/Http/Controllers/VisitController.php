<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Visit;
use App\Models\Status;
use App\Models\Patient;
use App\Models\ProblemCatalog;
use App\Models\ProblemTreatment;
use App\Models\Tooth;
use App\Models\Treatment;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period');
        $customStartDate = $request->query('startDate');
        $customEndDate  = $request->query('endDate');

        try {
            $visits = Visit::query()
                ->when($period === 'TODAY', fn($q) =>
                $q->whereDate('visit_date', now()))
                ->when($period === 'YESTERDAY', fn($q) =>
                $q->whereDate('visit_date', now()->subDay()))
                ->when($period === 'TOMORROW', fn($q) =>
                $q->whereDate('visit_date', now()->addDay()))
                ->when($period === 'THIS_WEEK', fn($q) =>
                $q->whereBetween('visit_date', [now()->startOfWeek(Carbon::SATURDAY), now()->endOfWeek()]))
                ->when($period === 'THIS_MONTH', fn($q) =>
                $q->whereBetween('visit_date', [now()->startOfMonth(), now()->endOfMonth()]))
                ->when($period === 'CUSTOME' && $customStartDate && $customEndDate, fn($q)  =>
                $q->whereBetween('visit_date', [$customStartDate, $customEndDate]))
                ->orderBy('visit_date')
                ->with('patient', 'dentist', 'status')
                ->get();

            $statuses = Status::all();
            $patients = Patient::all();
            $dentists = User::where('is_dentist', true)->get();

            return Inertia::render('Management/Visit', [
                'visits' => $visits,
                'statuses' => $statuses,
                'patients' => $patients,
                'dentists' => $dentists,
            ]);
        } catch (\Exception $exception) {
            return Inertia::render('Management/Visit', [
                'visits' => [],
                'error' => 'فشل في جلب البيانات.',
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'dentist_id' => 'required|string',
                'status_id' => 'required|integer',
                'visit_date' => 'nullable|date',
                'visit_time' => 'nullable|date_format:H:i',
                'is_done' => 'nullable|boolean',
            ]);

            // dd($request);
            $visit = Visit::create($validatedData);

            return redirect()->route('visits')->with('message', 'تم إضافة الزيارة بنجاح.');
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors([
                'error' => 'فشل في إضافة الزيارة.',
            ]);
        }
    }

    public function show($id)
    {
        $problemTreatments = ProblemTreatment::where('visit_id', $id)->with([
            'problem',
            'treatment',
            'tooth',
        ])->get();

        $problems = ProblemCatalog::all();
        $treatments = Treatment::all();
        $teeth = Tooth::all();

        $visit = Visit::findOrFail($id)->with([
            'patient',
            'dentist',
            'status',
            'teeth'
        ])->first();

        // dd($visit);
        return Inertia::render('Management/PatientVisit', [
            'visit' => $visit,
            'problemTreatments' => $problemTreatments,
            'problems' => $problems,
            'treatments' => $treatments,
            'teeth' => $teeth
        ]);
    }

    public function update(Request $request, Visit $visit)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|integer',
                'dentist_id' => 'required|string',
                'status_id' => 'required|integer',
                'visit_date' => 'nullable|date',
                'visit_time' => 'nullable',
                'is_done' => 'nullable|boolean',
            ]);

            $visit->update($validatedData);

            return redirect()->route('visits')->with('message', 'تم تعديل الزيارة بنجاح.');
        } catch (\Exception $exception) {
            dd($exception);
            return back()->withErrors([
                'error' => 'فشل في تعديل الزيارة.',
            ]);
        }
    }

    public function destroy(Visit $visit)
    {
        if ($visit === null) {
            return back()->withErrors([
                'error' => 'Visit not found.',
            ]);
        }

        try {
            $visit->delete();

            return redirect()->route('visits')->with('message', 'تم حذف الزيارة بنجاح.');
        } catch (\Exception $e) {
            $message = $e->getMessage();

            if (empty($message)) {
                $message = 'فشل في حذف الزيارة.';
            }

            return back()->withErrors([
                'error' => $message,
            ]);
        }
    }

    protected function applyPeriodFilter($query, $period, $customStartDate = null, $customEndDate = null)
    {
        $periodFilters = [
            'TODAY' => fn() => $query->whereDate('visit_date', now()),
            'YESTERDAY' => fn() => $query->whereDate('visit_date', now()->subDay()),
            'TOMORROW' => fn() => $query->whereDate('visit_date', now()->addDay()),
            'THIS_WEEK' => fn() => $query->whereBetween('visit_date', [now()->startOfWeek($weekStartsAt = Carbon::SATURDAY), now()->endOfWeek()]),
            'THIS_MONTH' => fn() => $query->whereBetween('visit_date', [now()->startOfMonth(), now()->endOfMonth()]),
            'CUSTOME' => fn() => $customStartDate !== null && $customEndDate !== null
                ? $query->whereBetween('date', [$customStartDate, $customEndDate])
                : null,
        ];

        if (array_key_exists($period, $periodFilters)) {
            $periodFilters[$period]();
            dd($periodFilters[$period]());
        }
    }
}
