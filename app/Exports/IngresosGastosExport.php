<?php

namespace App\Exports;

use App\Traits\ReportStyle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class IngresosGastosExport implements FromCollection, WithEvents, ShouldAutoSize,  WithCustomStartCell, WithColumnFormatting, WithTitle, WithDrawings,WithHeadings
{

    use ReportStyle;

    private $star_cell = 10;
    private $data;
    private $finish_header;
    private $title;
    private $heading;
    private $header_info;

    public function __construct($title,$heading,$data,$header_info)
    {
        $this->title = $title;
        $this->heading = $heading;
        $this->data = $data;
        $this->header_info = $header_info;
    }

    //setear collection de datos
    public function collection()
    {
         return $this->data;
    }

    //setear heading, nombre de columnas
    public function headings() : array
    {
        return $this->heading;
    }

    //setear inicio en libro excel
    public function startCell(): string
    {
        return 'A'.$this->star_cell;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER
        ];
    }


    //logo
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo_ascilo');
        $drawing->setPath(public_path('img/logo.jpg'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A2');

        return $drawing;
    }


    //configuración principal del reporte
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                //titulos y header principal
                $event->sheet->mergeCells('C2:F4');
                $event->sheet->setCellValue('C2', mb_strtoupper('REPORTE DE INGRESOS/GASTOS, ASCILO BUENA VISTA', 'UTF-8'));


                $event->sheet->mergeCells('A5:B5');
                $event->sheet->setCellValue('A5','ASCILO BUENA VISTA');

                //$event->sheet->mergeCells('B6:C6');
                //$event->sheet->setCellValue('A6','PLANILLA NO:');
                //$event->sheet->setCellValue('B6',$this->planilla->quincena.'-'.$this->planilla->anio->anio);

                //$event->sheet->mergeCells('B7:C7');
                $event->sheet->setCellValue('A7','PERIODO DEL: ');
                $event->sheet->setCellValue('B7',$this->header_info['from']);

                //$event->sheet->mergeCells('E7:F7');
                $event->sheet->setCellValue('C7','AL: ');
                $event->sheet->setCellValue('D7',$this->header_info['to']);


                $styleHeadings = $this->getStyleToHead();
                $styleHeaderArray = $this->getStyleToTitle(); 

                $event->sheet->getStyle('A1:E8')->applyFromArray($styleHeaderArray);//title header

                $event->sheet->getStyle('A10:E10')->applyFromArray($styleHeadings);
                $event->sheet->setAutoFilter('A10:E10');

                //$event->sheet->getStyle('A11:B11')->applyFromArray($styleArray); //setear rango de estilos para header
            },
        ];
    }



    //titulo de excel
    public function title(): string
    {
        return $this->title;
    }
}
