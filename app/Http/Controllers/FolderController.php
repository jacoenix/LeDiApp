<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use ZipArchive;

class FolderController extends Controller
{
    public function destroy($patient, Folder $folder)
    {
        $this->deleteFolderAndContents($folder);
        $folder->delete();

        return response()->noContent();
    }
    public function update(Request $request, $patient, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder->update([
            'name' => $request->name,
        ]);

        return response()->json($folder);
    }

    private function deleteFolderAndContents(Folder $folder)
    {
        foreach ($folder->documents as $document) {
            Storage::delete($document->file_path);
            $document->delete();
        }

        foreach ($folder->children as $childFolder) {
            $this->deleteFolderAndContents($childFolder);
            $childFolder->delete();
        }
    }

    public function index($patientId)
    {
        $folders = Folder::where('patient_id', $patientId)->whereNull('parent_id')->with('children.documents')->get();

        return Inertia::render('Patients/FileExplorer', [
            'folders' => $folders,
            'patientId' => $patientId,
            'patients' => Patient::all(),
            'selectedPatient' => Patient::find($patientId),
        ]);
    }

    public function show(Folder $folder)
    {
        $folder->load('children.documents', 'documents');

        return Inertia::render('Patients/FileExplorer', [
            'folders' => [$folder],
            'parentFolder' => $folder->parent,
            'patientId' => $folder->patient_id,
            'patients' => Patient::all(),
            'selectedPatient' => $folder->patient,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        $folder = Folder::create([
            'name' => $request->name,
            'patient_id' => $request->patient_id,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(['folder' => $folder]);
    }

}







