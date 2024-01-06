<?php

namespace App\Imports;

use App\Models\Dato;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Point;
use App\Models\Metadata;

class LatamGasPlantsLocationsImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Gas plants - data' => $this,
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
        if (is_null($row['plant_name'])) {
            return null;
        }

        if (is_null($row['latitude'])) {
            return null;
        }

        if (is_null($row['longitude'])) {
            return null;
        }

        $exists = Point::where('name', $row['plant_name'])->where('lat', $row['latitude'])
            ->where('long', $row['longitude'])->get();

        if ($exists->count() > 0) {
            return null;
        }

        $point = new Point();

        $point->name = $row['plant_name'];
        $point->long = $row['longitude'];
        $point->lat = $row['latitude'];

        $point->save();

        $point->metadata()->saveMany([
            new Metadata(['key' => 'country', 'value' => is_null($row['country']) ? '' : $row['country']]),
            new Metadata(['key' => 'fuel', 'value' => is_null($row['fuel']) ? '' : $row['fuel']]),
            new Metadata(['key' => 'technology', 'value' => is_null($row['technology']) ? '' : $row['technology']]),
            new Metadata(['key' => 'start_year', 'value' => is_null($row['start_year']) ? '' : $row['start_year']]),
            new Metadata(['key' => 'retired_year', 'value' => is_null($row['retired_year']) ? '' : $row['retired_year']]),
            new Metadata(['key' => 'planned_retire', 'value' => is_null($row['planned_retire']) ? '' : $row['planned_retire']]),
            new Metadata(['key' => 'owner', 'value' => is_null($row['owner']) ? '' : $row['owner']]),
            new Metadata(['key' => 'parent', 'value' => is_null($row['parent']) ? '' : $row['parent']]),
            new Metadata(['key' => 'region', 'value' => is_null($row['region']) ? '' : $row['region']]),
            new Metadata(['key' => 'city', 'value' => is_null($row['city']) ? '' : $row['city']]),
            new Metadata(['key' => 'captive_industry_type', 'value' => is_null($row['captive_industry_type']) ? '' : $row['captive_industry_type']]),
            new Metadata(['key' => 'capacity_elec_mw', 'value' => is_null($row['capacity_elec_mw']) ? '' : $row['capacity_elec_mw']]),
            new Metadata(['key' => 'other_plant_names', 'value' => is_null($row['other_plant_names']) ? '' : $row['other_plant_names']]),
            new Metadata(['key' => 'captive_heat_power_both', 'value' => is_null($row['captive_heat_power_both']) ? '' : $row['captive_heat_power_both']]),
            new Metadata(['key' => 'captive_non_industry_use_heat_power_both_none', 'value' => is_null($row['captive_non_industry_use_heat_power_both_none']) ? '' : $row['captive_non_industry_use_heat_power_both_none']]),
            new Metadata(['key' => 'source', 'value' => 'Global Energy Monitor, Portal Energético para América Latina']),
            new Metadata(['key' => 'status', 'value' => is_null($row['status']) ? '' : $row['status']]),
            new Metadata(['key' => 'type', 'value' => 'gas plant']),
        ]);

        Metadata::where('value', '')->delete();

        return $point;
    }
}
