<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;
    protected $table = 'metadata';
    protected $fillable = ['key', 'value'];

    public function point()
    {
        return $this->belongsTo(Point::class);
    }
}
