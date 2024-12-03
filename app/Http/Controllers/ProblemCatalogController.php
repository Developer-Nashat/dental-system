<?php

namespace App\Http\Controllers;

use App\Models\ProblemCatalog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProblemCatalogController extends Controller
{
    private $customMessages =
    [
        'name.required' => 'يجب ان تدخل اسم تصنيف المشكلة',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $problemCatalogs = ProblemCatalog::all();

        return Inertia::render('Management/ProblemCatalog', [
            'problemCatalogs' => $problemCatalogs
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);

        $feilds = $request->validate(
            [
                'name' => 'required'
            ],
            $this->customMessages
        );

        ProblemCatalog::create($feilds);

        return redirect()->route('problemCatalogs')->with('message', 'تمت إضافة تصنيف المشكلة بنجاح.');
    }

    public function update(Request $request, ProblemCatalog $problemCatalog)
    {
        $feilds = $request->validate(
            [
                'name' => 'required',
            ],
            $this->customMessages
        );

        $problemCatalog->update($feilds);

        return redirect()->route('problemCatalogs')->with('message', 'تم العديل تصنيف المشكلة بنجاح.');
    }


    public function destroy(ProblemCatalog $problemCatalog)
    {
        $problemCatalog->delete();

        return redirect()->route('problemCatalogs')->with('message', 'تم حذف تصنيف المشكلة بنجاح.');
    }
}
