<?php

namespace App\Imports;

use App\Models\Dato;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Point;
use App\Models\Metadata;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LatamOilAndGasExtractLocationsImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Oil & gas extract - main' => $this,
        ];
    }

    public function headingRow()
    {
        return 1;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (is_null($row['unit_name'])) {
            return null;
        }

        if (is_null($row['latitude'])) {
            return null;
        }

        if (is_null($row['longitude'])) {
            return null;
        }

        $exists = Point::where('name', $row['unit_name'])->where('lat', $row['latitude'])
            ->where('long', $row['longitude'])->get();

        if ($exists->count() > 0) {
            return null;
        }

        $point = new Point();

        $point->name = $row['unit_name'];
        $point->long = $row['longitude'];
        $point->lat = $row['latitude'];

        $point->save();

        $point->metadata()->saveMany([
            new Metadata(['key' => 'status', 'value' => $row['status']]),
            new Metadata(['key' => 'source', 'value' => 'Global Energy Monitor, Portal Energético para América Latina']),
            new Metadata(['key' => 'status year', 'value' => is_null($row['status_year']) ? '' : $row['status_year']]),
            new Metadata(['key' => 'discovery year', 'value' => is_null($row['discovery_year']) ? '' : $row['discovery_year']]),
            new Metadata(['key' => 'production start year', 'value' => is_null($row['production_start_year']) ? '' : $row['production_start_year']]),
            new Metadata(['key' => 'operator', 'value' => is_null($row['operator']) ? '' : $row['operator']]),
            new Metadata(['key' => 'fuel type', 'value' => $row['fuel_type']]),
            new Metadata(['key' => 'country', 'value' => $row['country']]),
            new Metadata(['key' => 'owner', 'value' => is_null($row['owner']) ? '' : $row['owner']]),
            new Metadata(['key' => 'parent', 'value' => is_null($row['parent']) ? '' : $row['parent']]),
        ]);

        Metadata::where('value', '')->delete();

        return $point;
    }
}
