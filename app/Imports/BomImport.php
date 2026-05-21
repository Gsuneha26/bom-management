<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BomImport implements ToCollection, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public Collection $rows;

    public function collection(Collection $rows)
    {
        $this->rows = $rows;
    }
}
