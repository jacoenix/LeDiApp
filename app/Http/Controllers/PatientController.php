<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Folder;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('dashboard')->with('success', 'Patient deleted successfully');
    }

    public function index()
    {
        $activePatients = Patient::where('status', 'active')->get();
        $inactivePatients = Patient::where('status', 'inactive')->get();

        return Inertia::render('Dashboard', [
            'patients' => $activePatients,
            'inactivePatients' => $inactivePatients,
        ]);
    }


    public function info(Patient $patient)
    {
        $inactivePatients = Patient::where('status', 'inactive')->get();
        $activePatients = Patient::where('status', 'active')->get();
        return Inertia::render('Patients/Info', [
            'patients' => $activePatients,
            'selectedPatient' => $patient,
            'patient' => $patient,
            'inactivePatients' => $inactivePatients,
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patient_number' => 'required|integer|unique:patients,patient_number,' . $patient->id,
            'birth_date' => 'nullable|date',
            'therapy_for' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'information' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'mothers_name' => 'nullable|string|max:255',
            'fathers_name' => 'nullable|string|max:255',
            'parents_email' => 'nullable|string|email|max:255',
            'parents_work' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        // Update the patient with validated data
        $patient->update($validatedData);

        // Update the folder name if the patient's name has changed
        $folderName = $validatedData['last_name'] . ' ' . $validatedData['first_name'];
        Folder::where('patient_id', $patient->id)
            ->where('parent_id', null)
            ->update(['name' => $folderName]);

        return redirect()->route('patient.info', $patient->id)->with('success', 'Patient updated successfully');
    }



    public function create()
    {
        return Inertia::render('Patients/PatientCreate');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patient_number' => 'required|integer|unique:patients',
            'birth_date' => 'nullable|date',
            'therapy_for' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'information' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'mothers_name' => 'nullable|string|max:255',
            'fathers_name' => 'nullable|string|max:255',
            'parents_email' => 'nullable|string|email|max:255',
            'parents_work' => 'nullable|string',
        ]);

        // Fügen Sie den Standardstatus 'active' zu den validierten Daten hinzu
        $validatedData['status'] = 'active';

        // Erstellen Sie den Patienten mit den validierten Daten
        $patient = Patient::create($validatedData);

        // Erstellen Sie einen Ordner mit dem Namen des Patienten
        Folder::create([
            'name' => $patient->last_name . ' ' . $patient->first_name,
            'patient_id' => $patient->id,
            'parent_id' => null
        ]);

        return redirect()->route('patient.info', $patient->id)->with('success', 'Patient created successfully');
    }




    public function documents(Patient $patient)
    {
        // Laden Sie den Ordner des Patienten
        $folder = Folder::where('patient_id', $patient->id)->whereNull('parent_id')->first();

        if ($folder) {
            return redirect()->route('documents.index', ['patient' => $patient->id, 'folder' => $folder->id]);
        }

        return redirect()->route('documents.index', ['patient' => $patient->id]);
    }
}
