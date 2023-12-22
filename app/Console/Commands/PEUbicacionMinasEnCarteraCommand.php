<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Datasource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PEUbicacionMinasEnCarteraImport;

class PEUbicacionMinasEnCarteraCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:pe-ubicacion-minas-en-cartera';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar datos de ubicacion de minas en cartera en peru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'Mapa de Proyectos Mineros en Cartera 2023';
        $filename = 'temp/Excel.xlsx';
        $datasource = Datasource::where('name', $name)->first();
        $url = $datasource->url;
        $response = Http::get($url);
        Storage::disk('local')->put($filename, $response->body());
        Excel::import(new PEUbicacionMinasEnCarteraImport(), $filename, 'local');

    }
}
