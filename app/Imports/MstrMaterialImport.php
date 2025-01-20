<?php

namespace App\Imports;

use App\Models\MstrMaterial;
use App\Models\MstrLineGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MstrMaterialImport implements ToModel, WithHeadingRow
{
    protected $statusMessages = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info('Processing row: ' . json_encode($row));

        $p = DB::transaction(function () use ($row) {
            $lineGroup = MstrLineGroup::where('Lg_code', $row['linegroup_code'])->first();

            if (!$lineGroup) {
                Log::info('LineGroup not found: ' . $row['linegroup_code']);
                $this->statusMessages[] = false;
                return false;
            }

            Log::info('LineGroup found: ' . json_encode($lineGroup));

            MstrMaterial::create([
                '_id' => Str::uuid(),
                'Mt_number' => $row['material_number'],
                'Mt_lgId' => $lineGroup->_id,
                'Mt_desc' => $row['description'],
            ]);
            $this->statusMessages[] = true;
        });


    }

    public function getStatusMessages()
    {
        return $this->statusMessages;
    }
}