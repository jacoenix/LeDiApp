<?php

// In app/Http/Controllers/NoteController.php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Note;
use Inertia\Inertia;


class NoteController extends Controller
{
    // Methode zum Anzeigen der Notiz

    public function show()
    {
        $activePatients = Patient::where('status', 'active')->get();
        $inactivePatients = Patient::where('status', 'inactive')->get();
        // Die erste Notiz abrufen oder eine leere erstellen
        $note = Note::firstOrCreate([], ['content' => '']);
        return Inertia::render('NoteEditor', [
            'note' => $note,
            'patients' => $activePatients,
            'inactivePatients' => $inactivePatients,
        ]);
    }


    // Methode zum Speichern/Aktualisieren der Notiz
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        // Die erste Notiz abrufen oder eine neue erstellen
        $note = Note::firstOrCreate([]);
        $note->content = $request->content;
        $note->save();

        return response()->json(['message' => 'Notiz erfolgreich gespeichert!'], 201);
    }
}

