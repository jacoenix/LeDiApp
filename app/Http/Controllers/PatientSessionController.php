<?php

namespace App\Http\Controllers;

use App\Models\PatientSession;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientSessionController extends Controller
{
    public function index($patientId)
    {
        $inactivePatients = Patient::where('status', 'inactive')->get();
        $activePatients = Patient::where('status', 'active')->get();
        $patientsessions = PatientSession::where('patient_id', $patientId)->orderBy('session_date', 'desc')->get();

        return Inertia::render('Patients/Sessions', [
            'patientsessions' => $patientsessions,
            'patientId' => $patientId,
            'patients' => $activePatients,
            'selectedPatient' => Patient::find($patientId),
            'inactivePatients' => $inactivePatients,
        ]);
    }

    public function create()
    {
        $activePatients = Patient::where('status', 'active')->get();
        $sessions = PatientSession::with('patient')->orderBy('session_date', 'desc')->get();
        return Inertia::render('Patients/CreateSession', [
            'patients' => $activePatients,
            'sessions' => $sessions,

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'session_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'description' => 'required|string',
        ]);

        PatientSession::create($request->all());

        return redirect()->route('sessions.index', $request->patient_id)->with('success', 'Session created successfully');
    }

    public function edit(PatientSession $patientsession)
    {
        return Inertia::render('Patients/EditSession', [
            'patientsession' => $patientsession,
            'patients' => Patient::all(),
        ]);
    }

    public function update(Request $request, $patient, PatientSession $session)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required|string',
        ]);

        $session->update([
            'title' => $request->title,
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'description' => $request->description,
        ]);

        return response()->json($session);
    }

    public function destroy($patient, PatientSession $session)
    {
        $session->delete();
        return response()->json(['success' => true]);
    }


    public function export($patientId, PatientSession $session)
    {
        $patient = Patient::find($patientId);
        $pdf = PDF::loadView('pdf.session', compact('session', 'patient'));
        return $pdf->download('Klient-' . $patient->patient_number . '-' . $session->session_date .'.pdf');
    }

    public function deleteAll(Request $request, $patient)
    {
        try {
            PatientSession::where('patient_id', $patient)->delete();
            return response()->json(['message' => 'All sessions deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete sessions'], 500);
        }
    }


    public function show(Patient $patient, PatientSession $session)
    {
        return response()->json([
            'session' => $session,
        ]);
    }
}

