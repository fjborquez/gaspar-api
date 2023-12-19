<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datasource extends Model
{
    use HasFactory;
    protected $table = 'datasources';
    protected $fillable = ['id', 'name', 'url', 'last_update'];
}
