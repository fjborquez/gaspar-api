<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Datasource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PEUbicacionMinasEnProduccionImport;

class PEUbicacionMinasEnProduccionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:pe-ubicacion-minas-en-produccion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar datos de ubicacion de minas en produccion en peru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'Mapa de Principales Unidades Mineras en Producción 2023';
        $filename = 'temp/2023_%20MAPA%20DE%20PRODUCCION.xlsx';
        $datasource = Datasource::where('name', $name)->first();
        $url = $datasource->url;
        $response = Http::get($url);
        Storage::disk('local')->put($filename, $response->body());
        Excel::import(new PEUbicacionMinasEnProduccionImport(), $filename, 'local');
    }
}
