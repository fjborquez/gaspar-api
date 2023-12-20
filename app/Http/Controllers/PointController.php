<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;

class PointController extends Controller
{
    public function index(Request $request)
    {
        $wheres = $request->get('where', []);
        $points = new Point();

        foreach ($wheres as $where)
        {
            $points = $points->whereRelation('metadata', 'key', '=', $where['key']);
            $points = $points->whereRelation('metadata', 'value', '=', $where['value']);
        }

        return $points->with('metadata')->get();

    }
}
