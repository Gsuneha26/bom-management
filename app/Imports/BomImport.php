<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BomImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public Collection $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows->filter(function ($row) {
            return $row->filter(function ($value) {
                return $value !== null && $value !== '';
            })->isNotEmpty();
        });
    }
}
