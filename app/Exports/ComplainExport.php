<?php

namespace App\Exports;

use App\Complain;
use Maatwebsite\Excel\Concerns\FromCollection;

class ComplainExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Complain::all(['id']);
    }
}
