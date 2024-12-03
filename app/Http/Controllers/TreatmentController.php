<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TreatmentController extends Controller
{
    private $customMessages =
    [
        'name.required' => 'يجب ان تدخل اسم المعالجة',
        'price.required' => 'يجب ان تدخل السعر',
        'price.decimal' => 'يجب على السعر ان يكون رقماً',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $treatements = Treatment::all();

        return Inertia::render('Management/Treatment', [
            'treatments' => $treatements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        $feilds = $request->validate(
            [
                'name' => 'required',
                'price' => ['required', 'decimal:0,6'],
                'description' => 'sometimes'
            ],
            $this->customMessages
        );

        Treatment::create($feilds);

        return redirect()->route('treatments')->with('message', 'تمت إضافة المعالجة بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function update(Request $request, Treatment $treatment)
    {
        $feilds = $request->validate(
            [
                'name' => 'required',
                'price' => ['required', 'decimal:0,6'],
                'description' => 'sometimes'
            ],
            $this->customMessages
        );

        $treatment->update($feilds);

        return redirect()->route('treatments')->with('message', 'تم العديل المعالجة بنجاح.');
    }


    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return redirect()->route('treatments')->with('message', 'تم حذف المعالجة بنجاح.');
    }
}
