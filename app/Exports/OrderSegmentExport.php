<?php

namespace App\Exports;

use App\Models\OrderSegment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderSegmentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $orderSegments;

    public function __construct($orderSegments)
    {
        $this->orderSegments = $orderSegments;
    }

    public function collection()
    {
        return $this->orderSegments;
    }

    public function headings(): array
    {
        return [

            'No Order',
            'Consumable ID',
            'NPK Sect',
            'NPK Dept',
            'NPK PJ',

            'Jumlah',
            'line'

        ];
    }

    public function map($orderSegment): array
    {
        return $orderSegment->mstrApprs->map(function ($mstrAppr) use ($orderSegment) {
            return [

                $orderSegment->noOrder ?? '',
                $mstrAppr->consumable->Cb_desc . "(" . $mstrAppr->consumable->Cb_number . ")" ?? '',
                $mstrAppr->sect->name ?? '',
                $mstrAppr->dept->name ?? '',
                $mstrAppr->pj->name ?? '',

                $mstrAppr->jumlah ?? '',
                $mstrAppr->lineFrom ?? '',

            ];
        })->toArray();
    }
}