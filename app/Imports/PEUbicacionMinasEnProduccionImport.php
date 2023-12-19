<?php

namespace App\Imports;

use App\Models\Point;
use App\Models\Metadata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PEUbicacionMinasEnProduccionImport implements ToModel, WithHeadingRow
{

    public function headingRow(): int
    {
        return 3;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (is_null($row['unidad'])) {
            return null;
        }

        $exists = Point::where('name', $row['unidad'])->where('lat', $row['latitud'])
            ->where('long', $row['longitud'])->get();

        if ($exists->count() > 0) {
            return null;
        }

        $point = new Point();

        $point->name = $row['unidad'];
        $point->long = $row['longitud'];
        $point->lat = $row['latitud'];

        $point->save();

        $point->metadata()->saveMany([
            new Metadata(['key' => 'metodo de explotacion', 'value' => $row['metodo_de_explotacion']]),
            new Metadata(['key' => 'titular', 'value' => $row['titular']]),
            new Metadata(['key' => 'region', 'value' => $row['region']]),
            new Metadata(['key' => 'provincia', 'value' => $row['provincia']]),
            new Metadata(['key' => 'distrito', 'value' => $row['distrito']]),
            new Metadata(['key' => 'producto', 'value' => $row['producto']]),
            new Metadata(['key' => 'tipo', 'value' => 'mina']),
            new Metadata(['key' => 'pais', 'value' => 'peru']),
            new Metadata(['key' => 'estado', 'value' => 'produccion']),
        ]);

        return $point;
    }
}
