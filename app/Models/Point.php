<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Point extends Model
{
    use HasFactory;
    protected $table = 'points';
    protected $fillable = ['name', 'lat', 'long'];

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class);
    }
}
