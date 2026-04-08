<?php

namespace App\Http\Controllers;

use App\Models\Scenario;

class ScenarioController extends Controller
{
    public function index()
    {
        return response()->json([
            'scenarios' => Scenario::query()
                ->select(['key', 'label'])
                ->orderBy('label')
                ->get(),
        ]);
    }
}