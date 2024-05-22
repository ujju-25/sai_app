<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContactsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Contact([
            'name'  => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
        ]);
    }
}
