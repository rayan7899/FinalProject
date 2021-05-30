<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function student_documents($national_id)
    {
        $documents = Storage::disk('studentDocuments')->files($national_id);
        $receipts = Storage::disk('studentDocuments')->files($national_id . '/receipts');
        $privateDocs = Storage::disk('studentDocuments')->files($national_id . '/privateStateDoc');

        return  array(
            'documents' => $documents,
            'receipts'  => $receipts,
            'privateDocs' => $privateDocs,
        );
    }

    public function get_student_document($path)
    {
        return response()->file(Storage::disk('studentDocuments')->path($path));
    }

    public function get_student_document_api($national_id, $filename)
    {
        return response()->file(Storage::disk('studentDocuments')->path('/' . $national_id . '/receipts/' . $filename));
    }

    public function downloadBackup()
    {
        $backupFiles = Storage::disk('backups')->allFiles('bct-website');
        if (count($backupFiles) > 0) {
            $latestBackupFileName = Storage::disk('backups')->path($backupFiles[count($backupFiles) - 1]);
            return response()->download($latestBackupFileName);
        }
    }
}
