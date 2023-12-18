<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;

class PointController extends Controller
{
    public function index()
    {
        return Point::with('metadata')->get();
    }
}
