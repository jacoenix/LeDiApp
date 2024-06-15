<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use App\Models\Patient;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;
use ZipArchive;
use Chumper\Zipper\Zipper;
use Spatie\Backup\Tasks\Backup\Zip;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller

{
    public function destroy($patient, Document $document)
    {
        Storage::delete($document->file_path);
        $document->delete();

        return response()->noContent();
    }

    public function index(Patient $patient, Folder $folder = null)
    {
        $inactivePatients = Patient::where('status', 'inactive')->get();
        $activePatients = Patient::where('status', 'active')->get();

        // Standardordner des Patienten finden oder erstellen
        $defaultFolder = Folder::firstOrCreate([
            'name' => $patient->last_name . ' ' . $patient->first_name,
            'patient_id' => $patient->id,
            'parent_id' => null
        ]);

        $currentFolder = $folder ?: $defaultFolder;

        if ($folder === null) {
            // Wenn kein spezifischer Ordner angegeben ist, leiten wir auf den Standardordner weiter
            return redirect()->route('documents.index', ['patient' => $patient->id, 'folder' => $defaultFolder->id]);
        }

        $folders = Folder::where('parent_id', $currentFolder->id)
            ->where('patient_id', $patient->id)
            ->get();

        $documents = Document::where('folder_id', $currentFolder->id)->get();

        $path = $this->getFolderPath($currentFolder);

        return Inertia::render('Documents/Index', [
            'folders' => $folders,
            'currentFolder' => $currentFolder,
            'documents' => $documents,
            'path' => $path,
            'selectedPatient' => $patient,
            'patients' => $activePatients,
            'auth' => auth()->user(),
            'inactivePatients' => $inactivePatients,
        ]);
    }


    public function storeFolder(Request $request, Patient $patient, Folder $folder = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $newFolder = new Folder([
            'name' => $request->name,
            'parent_id' => $folder ? $folder->id : null,
            'patient_id' => $patient->id,
        ]);

        $newFolder->save();

        return response()->json($newFolder);
    }

    public function storeDocument(Request $request, Patient $patient, Folder $folder = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file',
        ]);

        $document = new Document([
            'name' => $request->name,
            'folder_id' => $folder ? $folder->id : null,
            'patient_id' => $patient->id,
            'file_path' => $request->file('file')->store('documents'),
        ]);

        $document->save();

        return response()->json($document);
    }
    public function update(Request $request, $patient, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $document->update([
            'name' => $request->name,
        ]);

        return response()->json($document);
    }


    public function download(Request $request, $patientId, $folderId, $documentId)
    {
        $document = Document::findOrFail($documentId);
        $path = storage_path('app/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $document->name, [
            'Content-Type' => mime_content_type($path)
        ]);
    }

    public function downloadZip(Patient $patient, Folder $folder)
    {
        $zip = new ZipArchive;
        $fileName = $folder->name . '.zip';
        $filePath = storage_path('app/public/' . $fileName);

        \Log::info('ZIP file path: ' . $filePath);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $result = $zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($result === TRUE) {
            $folder->load('documents', 'children');
            $this->addFolderToZip($zip, $folder);
            $zip->close();

            if (file_exists($filePath)) {
                \Log::info('ZIP file created successfully: ' . $filePath);
                return response()->download($filePath)->deleteFileAfterSend(true);
            } else {
                \Log::error('Failed to create ZIP file: ' . $filePath);
                return response()->json(['error' => 'Failed to create ZIP file'], 500);
            }
        } else {
            \Log::error('Unable to open ZIP file for writing: ' . $filePath . ' Error code: ' . $result);
            return response()->json(['error' => 'Unable to create zip file', 'code' => $result], 500);
        }
    }

    private function addFolderToZip(ZipArchive $zip, Folder $folder, $parentFolderPath = '')
    {
        $folderPath = $parentFolderPath . $folder->name . '/';
        foreach ($folder->documents as $document) {
            $filePath = storage_path('app/' . $document->file_path);
            \Log::info('Adding document to ZIP: ' . $filePath);

            if (file_exists($filePath)) {
                $zip->addFile($filePath, $folderPath . $document->name);
            } else {
                \Log::error('Document file does not exist: ' . $filePath);
            }
        }

        foreach ($folder->children as $subfolder) {
            $this->addFolderToZip($zip, $subfolder, $folderPath);
        }
    }








    private function getFolderPath($folder)
    {
        $path = [];
        while ($folder) {
            array_unshift($path, $folder);
            $folder = $folder->parent;
        }
        return $path;
    }
}

