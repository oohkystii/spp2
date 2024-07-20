<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WaliImport implements ToModel, WithStartRow, WithValidation
{
    /**
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (!array_filter($row)) {
            return null;
        }

        return new User([
            'name' => $row[1],
            'nohp' => $row[2],
            'email' => $row[3],
            'password' => bcrypt($row[4]), // Hash the password
            'akses' => $row[5],
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Start from the second row to skip headers
    }

    public function rules(): array
    {
        return [
            '1' => 'required|unique:users,nohp', // Ensure unique phone number
            '2' => 'required|string|max:255',
            '3' => 'required|email|max:255',
            '4' => 'required', // Minimum password length
            '5' => 'required|string|max:255',
            // Add any additional validation rules if necessary
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '1.unique' => 'No HP harus unik.',
            '1.required' => 'No HP tidak boleh kosong.',
            '2.required' => 'Nama tidak boleh kosong.',
            '3.required' => 'Email tidak boleh kosong.',
            '3.email' => 'Email tidak valid.',
            '4.required' => 'Password tidak boleh kosong.',
            '5.required' => 'Akses tidak boleh kosong.',
        ];
    }
}
