<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Siswa;
use Illuminate\Database\QueryException;

class SiswaImportController extends Controller
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

        foreach ($data as $index => $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            // Check for empty fields
            if (empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4]) || empty($row[5]) || empty($row[6])) {
                $incompleteEntries[] = $index + 1; // Store the row number
                continue; // Skip this entry
            }

            try {
                // Insert new record
                Siswa::create([
                    'nisn' => $row[1],
                    'nama' => $row[2],
                    'jurusan' => $row[3],
                    'kelas' => $row[4],
                    'angkatan' => $row[5],
                    'biaya_id' => $row[6],
                ]);
            } catch (QueryException $e) {
                // Check for duplicate entry error
                if ($e->errorInfo[1] == 1062) {
                    $duplicateEntries[] = $row[1]; // Store duplicate NISN
                } else {
                    // Handle other exceptions as needed
                    return redirect()->route('siswa.index')->with('error', 'Gagal mengimpor data. Terjadi kesalahan: ' . $e->getMessage());
                }
            }
        }

        // Prepare messages for duplicates or incomplete entries
        if (!empty($duplicateEntries)) {
            return redirect()->route('siswa.index')->with('warning', 'Beberapa NISN sudah ada: ' . implode(', ', $duplicateEntries));
        }

        if (!empty($incompleteEntries)) {
            return redirect()->route('siswa.index')->with('error', 'Beberapa baris tidak lengkap: ' . implode(', ', $incompleteEntries) . '. Pastikan semua kolom diisi.');
        }

        return redirect()->route('siswa.index')->with('success', 'Data berhasil diimpor.');
    }
}
