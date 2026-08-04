<?php

namespace App\Http\Controllers;

use App\Models\PlantTemplate;
use App\Models\PlantCategory;

class PlantTemplateController extends Controller
{
    public function index()
    {
        $categories = PlantCategory::with('templates')->get();

        return response()->json($categories);
    }
}
