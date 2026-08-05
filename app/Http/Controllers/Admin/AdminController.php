<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Garden;
use App\Models\GardenPlot;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalGardens = Garden::count();
        $totalPlots = GardenPlot::count();
        $premiumUsers = User::whereIn('role', ['pro', 'premium'])->count();

        return view('admin.dashboard', compact('totalUsers', 'totalGardens', 'totalPlots', 'premiumUsers'));
    }

    public function users()
    {
        $users = User::withCount('gardens')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function plants(Request $request)
    {
        $query = \App\Models\PlantTemplate::with('category');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name_id', 'like', "%{$search}%")
                  ->orWhere('scientific_name', 'like', "%{$search}%");
        }

        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->category}%");
            });
        }

        $plants = $query->paginate(10)->withQueryString();
        $categories = \App\Models\PlantCategory::all();
        
        return view('admin.plants', compact('plants', 'categories'));
    }

    public function careTemplates(Request $request)
    {
        $query = \App\Models\PlantTemplate::with('category');
        
        if ($request->has('sort') && $request->sort == 'name') {
            $query->orderBy('name_id', 'asc');
        } elseif ($request->has('sort') && $request->sort == 'category') {
            $query->join('plant_categories', 'plant_templates.category_id', '=', 'plant_categories.id')
                  ->orderBy('plant_categories.name', 'asc')
                  ->select('plant_templates.*');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $templates = $query->get();
        return view('admin.care-templates', compact('templates'));
    }

    public function storePlant(Request $request)
    {
        $validated = $request->validate([
            'name_id' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'category_id' => 'required|exists:plant_categories,id',
            'germination_day' => 'nullable|integer',
            'seedling_day' => 'nullable|integer',
            'harvest_start_day' => 'required|integer',
            'soil_ph_min' => 'required|numeric',
            'soil_ph_max' => 'required|numeric',
        ]);

        \App\Models\PlantTemplate::create($validated);
        return response()->json(['success' => true]);
    }

    public function updatePlant(Request $request, \App\Models\PlantTemplate $plant)
    {
        $validated = $request->validate([
            'name_id' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'category_id' => 'required|exists:plant_categories,id',
            'germination_day' => 'nullable|integer',
            'seedling_day' => 'nullable|integer',
            'harvest_start_day' => 'required|integer',
            'soil_ph_min' => 'required|numeric',
            'soil_ph_max' => 'required|numeric',
        ]);

        $plant->update($validated);
        return response()->json(['success' => true]);
    }

    public function updateCareRules(Request $request, \App\Models\PlantTemplate $plant)
    {
        $validated = $request->validate([
            'care_rules' => 'nullable|array',
        ]);

        $plant->update(['care_rules' => $validated['care_rules']]);
        return response()->json(['success' => true]);
    }

    public function destroyPlant(\App\Models\PlantTemplate $plant)
    {
        $plant->delete();
        return response()->json(['success' => true]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:free,pro,premium,admin'
        ]);

        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot change your own role.'], 403);
        }

        $user->update(['role' => $request->role]);
        return response()->json(['success' => true]);
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete yourself.'], 403);
        }

        $user->delete();
        return response()->json(['success' => true]);
    }
}
