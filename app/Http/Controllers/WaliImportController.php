<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Database\QueryException;

class WaliImportController extends Controller
{
    public function store(Request $request)
    {
        // Validate file
        $request->validate([
            'template' => 'required|mimes:xlsx,xls'
        ]);

        // Load the uploaded file
        $file = $request->file('template');
        $spreadsheet = IOFactory::load($file->getPathname());

        // Get the first sheet
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        // Skip the header (first row)
        $isFirstRow = true;
        $duplicateEntries = [];
        $incompleteEntries = [];
        $validUsers = [];

        foreach ($data as $index => $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            // Check for empty fields
            if (empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4]) || empty($row[5])) {
                $incompleteEntries[] = $index + 1; // Store the row number
                continue; // Skip this entry
            }

            // Prepare valid user data for insertion
            $validUsers[] = [
                'name' => $row[1],
                'nohp' => $row[2],
                'email' => $row[3],
                'password' => bcrypt($row[4]), // Hash the password
                'akses' => $row[5],
            ];
        }

        // Insert valid records while checking for duplicates
        foreach ($validUsers as $userData) {
            try {
                User::create($userData);
            } catch (QueryException $e) {
                // Check for duplicate entry error
                if ($e->errorInfo[1] == 1062) {
                    $duplicateEntries[] = $userData['nohp']; // Store duplicate No HP
                } else {
                    // Handle other exceptions as needed
                    return redirect()->route('wali.index')->with('error', 'Gagal mengimpor data. Terjadi kesalahan: ' . $e->getMessage());
                }
            }
        }

        
        // Prepare messages for duplicates or incomplete entries
        if (!empty($duplicateEntries)) {
            return redirect()->route('wali.index')->with('warning', 'Beberapa No HP sudah ada: ' . implode(', ', $duplicateEntries));
        }

        if (!empty($incompleteEntries)) {
            return redirect()->route('wali.index')->with('error', 'Beberapa baris tidak lengkap: ' . implode(', ', $incompleteEntries) . '. Pastikan semua kolom diisi');
        }

        return redirect()->route('wali.index')->with('success', 'Data berhasil diimpor.');
    }
}
