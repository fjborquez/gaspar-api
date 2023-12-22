<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Point;
use App\Models\Metadata;

class PEUbicacionMinasEnCarteraImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 5;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if (is_null($row[3])) {
            return null;
        }

        $exists = Point::where('name', $row[3])->where('lat', $row[9])
            ->where('long', $row[8])->get();

        if ($exists->count() > 0) {
            return null;
        }

        $point = new Point();

        $point->name = $row[3];
        $point->long = $row[8];
        $point->lat = $row[9];

        $point->save();

        $point->metadata()->saveMany([
            new Metadata(['key' => 'clasificacion', 'value' => $row[1]]),
            new Metadata(['key' => 'empresa', 'value' => $row[2]]),
            new Metadata(['key' => 'departamento', 'value' => $row[4]]),
            new Metadata(['key' => 'provincia', 'value' => $row[5]]),
            new Metadata(['key' => 'distrito', 'value' => $row[6]]),
            new Metadata(['key' => 'producto', 'value' => $row[7]]),
            new Metadata(['key' => 'tipo', 'value' => 'mina']),
            new Metadata(['key' => 'pais', 'value' => 'peru']),
            new Metadata(['key' => 'estado', 'value' => 'en cartera']),
        ]);

        return $point;
    }
}
