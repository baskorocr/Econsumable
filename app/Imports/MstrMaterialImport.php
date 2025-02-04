<?php

namespace App\Imports;

use App\Models\MstrConsumable;
use App\Models\MstrLineGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MstrMaterialImport implements ToModel, WithHeadingRow
{
    protected $statusMessages = [];

    public function model(array $row)
    {
        Log::info('Processing row: ' . json_encode($row));

        // Normalize array keys to lowercase
        $row = array_change_key_case($row, CASE_LOWER);

        // Validate required fields
        if (empty($row['material']) || empty($row['io'])) {
            Log::error('Missing required fields: ' . json_encode($row));
            $this->statusMessages[] = false;
            return null;
        }

        return DB::transaction(function () use ($row) {
            $lineGroup = MstrLineGroup::where('Lg_code', $row['linegroup_code'])->first();

            if (!$lineGroup) {
                Log::info('LineGroup not found: ' . $row['linegroup_code']);
                $this->statusMessages[] = false;
                return null;
            }

            Log::info('LineGroup found: ' . json_encode($lineGroup));

            return MstrConsumable::create([
                '_id' => Str::uuid(),
                'Cb_number' => $row['material'],
                'Cb_lgId' => $lineGroup->_id,
                'Cb_desc' => $row['description'],
                'Cb_type' => $row['type'],
                'Cb_IO' => $row['io'],
            ]);
        });
    }

    public function getStatusMessages()
    {
        return $this->statusMessages;
    }
}