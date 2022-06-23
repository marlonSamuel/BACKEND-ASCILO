<?php

namespace App\Exports;

use App\Exports\PagosFundacionExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PagosFundacionSheetExport implements WithMultipleSheets
{
   protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];
        foreach ($this->data as $key => $d) {
            //enviar data a clientexport, titulo hoja, columnas, información de encabezado
            $sheets[] = new PagosFundacionExport($key,$d[0],$d[1],$d[2]);
        }

        return $sheets;
    }
}
