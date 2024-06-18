<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiagnosisController extends Controller
{
    public function index()
    {
        $diagnoses = Diagnosis::all();
        return Inertia::render('Diagnosis/DiagnosisInfo', ['diagnoses' => $diagnoses]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Diagnosis::create($request->all());

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis created successfully.');
    }

    public function update(Request $request, Diagnosis $diagnosis)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $diagnosis->update($request->all());

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis updated successfully.');
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosis->delete();

        return redirect()->route('diagnoses.index')->with('success', 'Diagnosis deleted successfully.');
    }
}

