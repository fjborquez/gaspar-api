<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LatamOilAndGasExtractLocationsImport;
use App\Models\Datasource;

class LatamOilAndGasExtractLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:latam-oil-and-gas-extract-locations-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Oil and gas extract locations import';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'Oil and gas extract locations in latin america 2023';
        $filename = 'temp/Excel.xlsx';
        $datasource = Datasource::where('name', $name)->first();
        $url = $datasource->url;
        $response = Http::get($url);
        Storage::disk('local')->put($filename, $response->body());
        Excel::import(new LatamOilAndGasExtractLocationsImport(), $filename, 'local');
    }
}
