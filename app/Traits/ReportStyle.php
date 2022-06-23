<?php
namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;

trait ReportStyle
{
	 protected function getStyleToHead()
    {
        return  [
                    //Set border Style
                    'borders' => [ 
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            //'color' => ['argb' => 'EB2B04'],
                        ],

                    ],

                    //Set font style
                    'font' => [
                        'name'      =>  'Calibri',
                        'size'      =>  11,
                        'bold'      =>  true,
                        'color' => ['argb' => '000000'],
                    ],

                    //Set background style
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'a1dae7',
                        ] 
                    ],

                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ] 
             ]; 
    }

    
    protected function getStyleToTitle()
    {
        return  [
                    //Set border Style
                    'borders' => [ 
                        'outline' => [
                            //'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            //'color' => ['argb' => 'EB2B04'],
                        ],

                    ],

                    //Set font style
                    'font' => [
                        'name'      =>  'Calibri',
                        'size'      =>  11,
                        'bold'      =>  true,
                        'color' => ['argb' => '000000'],
                    ],

                    //Set background style
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            //'rgb' => 'F6F6F6',
                        ] 
                    ] 
             ]; 
    }

    protected function alignmentCenter()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ]
        ];
    }

    protected function alignmentRight()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            ]
        ];
    }

    protected function alignmentLeft()
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
    }


    protected function getSizeLetter($size)
    {
        return [
            'font' => [
                'bold' => true,
                'size'      =>  $size
            ]
        ];
    }

    protected function getBorder()
    {
        return [
            'borders' => [ 
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    //'color' => ['argb' => 'EB2B04'],
                ],

            ]
        ];
    }

    protected function getBold()
    {
        return  [
                    //Set border Style
                    'borders' => [ 
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                            //'color' => ['argb' => 'EB2B04'],
                        ],

                    ],

                    //Set font style
                    'font' => [
                        'name'      =>  'Calibri',
                        'size'      =>  11,
                        'bold'      =>  true,
                        'color' => ['argb' => '000000'],
                    ]
             ]; 
    }
}