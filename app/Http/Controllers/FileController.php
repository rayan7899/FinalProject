<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function get_trainer_document($national_id, $filename)
    {
        $files = Storage::disk('trainerDocuments')->files($national_id);
        return response()->file(Storage::disk('trainerDocuments')->path($files[array_key_first(preg_grep('/' . $filename . '/', $files))]));
    }
    public function get_trainer_file_extension($national_id, $filename)
    {
        $files = Storage::disk('trainerDocuments')->files($national_id);
        $filePath = $files[array_key_first(preg_grep('/' . $filename . '/', $files))];
        $ext = explode('.',$filePath);
        return end($ext);
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

    public function get_all_documents_api($national_id)
    {
        try {
            $user = User::where('national_id', $national_id)->first();
            $payments = $user->student->payments()->where('accepted', '=', 1)->get();
            $imgs = [];
            $paths = Storage::disk('studentDocuments')->allFiles('/' . $national_id . '/receipts/');
            foreach ($payments as $payment) {
                array_push($imgs, $payment->receipt_file_id);
            };
            return response($imgs);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return response(['message' => $e], 422);
        }
    }

    public function excelReport($filename)
    {
        $file = Storage::disk('excelFiles')->path($filename);
        return response()->download($file);
    }
}
