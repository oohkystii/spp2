<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithStartRow, WithValidation
{
    /**
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!array_filter($row)) {
            return null;
        }

        return new Siswa([
            'nisn' => $row[1],
            'nama' => $row[2],
            'jurusan' => $row[3],
            'kelas' => $row[4],
            'angkatan' => $row[5],
            'biaya_id' => $row[6],
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '1' => 'required|unique:siswas,nisn',
            '2' => 'required',
            '3' => 'required',
            '4' => 'required',
            '5' => 'required',
            '6' => 'required|exists:biayas,id',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '1.unique' => 'Data NISN harus unik',
            '1.required' => 'Data NISN tidak boleh kosong',
            '2.required' => 'Data Nama tidak boleh kosong',
            '3.required' => 'Data Jurusan tidak boleh kosong',
            '4.required' => 'Data Kelas tidak boleh kosong',
            '5.required' => 'Data Angkatan tidak boleh kosong',
            '6.required' => 'Data Biaya ID tidak boleh kosong',
            '6.exists' => 'Data Biaya ID tidak ditemukan di dalam tabel biaya',
        ];
    }
}
