<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Garden;
use App\Models\Plant;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalGardens = Garden::count();
        $totalPlants = Plant::whereIn('status', ['ACTIVE', 'PRODUCTIVE', 'HARVESTING'])->count();
        $premiumUsers = User::whereIn('role', ['pro', 'premium'])->count();
        
        // Count successful harvests (completed harvest events)
        $successfulHarvests = \App\Models\Event::whereHas('eventType', function($q) {
            $q->where('code', 'like', '%HARVEST%');
        })->where('status', 'COMPLETED')->count();

        // Popular plants
        $popularPlants = Plant::select('plant_template_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('plant_template_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('plantTemplate')
            ->get();

        // Top activities
        $topActivities = \App\Models\Event::select('event_type_id', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->where('status', 'COMPLETED')
            ->groupBy('event_type_id')
            ->orderBy('total', 'desc')
            ->take(4)
            ->with('eventType')
            ->get();
        $totalCompletedEvents = \App\Models\Event::where('status', 'COMPLETED')->count();

        // Today's activities
        $todayActivities = \App\Models\Event::with(['plant.garden.user', 'eventType'])
            ->whereDate('scheduled_date', today())
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Average Harvest Age
        $avgHarvestAge = \App\Models\PlantTemplate::avg('harvest_start_day') ?? 0;
        $avgHarvestAge = round($avgHarvestAge);

        return view('admin.dashboard', compact(
            'totalUsers', 'totalGardens', 'totalPlants', 'premiumUsers', 'successfulHarvests',
            'popularPlants', 'topActivities', 'todayActivities', 'totalCompletedEvents', 'avgHarvestAge'
        ));
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

    public function badges()
    {
        $badges = \App\Models\Badge::withCount('users')->get();
        $users = User::orderBy('name')->get();
        return view('admin.badges', compact('badges', 'users'));
    }

    public function storeBadge(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|string',
        ]);

        \App\Models\Badge::create($validated);
        return redirect()->back()->with('success', 'Badge berhasil dibuat!');
    }

    public function updateBadge(Request $request, \App\Models\Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon_url' => 'nullable|string',
        ]);

        $badge->update($validated);
        return redirect()->back()->with('success', 'Badge berhasil diperbarui!');
    }

    public function destroyBadge(\App\Models\Badge $badge)
    {
        $badge->delete();
        return redirect()->back()->with('success', 'Badge berhasil dihapus!');
    }

    public function awardBadgeToUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_id' => 'required|exists:badges,id',
        ]);

        $user = User::findOrFail($validated['user_id']);
        if (!$user->badges()->where('badge_id', $validated['badge_id'])->exists()) {
            $user->badges()->attach($validated['badge_id'], ['awarded_at' => now()]);
            return redirect()->back()->with('success', "Badge berhasil diberikan ke {$user->name}!");
        }

        return redirect()->back()->with('info', "Pengguna sudah memiliki badge ini.");
    }

    public function weather()
    {
        $weatherRules = \App\Models\WeatherRule::all();
        $activityRules = \App\Models\ActivityWeatherRule::all();
        return view('admin.weather', compact('weatherRules', 'activityRules'));
    }

    public function storeWeatherRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'weather_field' => 'required|string',
            'operator' => 'required|string',
            'threshold' => 'required|numeric',
            'severity' => 'required|string',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        \App\Models\WeatherRule::create($validated);
        return redirect()->back()->with('success', 'Aturan Peringatan berhasil dibuat!');
    }

    public function updateWeatherRule(Request $request, \App\Models\WeatherRule $weatherRule)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'weather_field' => 'required|string',
            'operator' => 'required|string',
            'threshold' => 'required|numeric',
            'severity' => 'required|string',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $weatherRule->update($validated);
        return redirect()->back()->with('success', 'Aturan Peringatan berhasil diperbarui!');
    }

    public function destroyWeatherRule(\App\Models\WeatherRule $weatherRule)
    {
        $weatherRule->delete();
        return redirect()->back()->with('success', 'Aturan Peringatan berhasil dihapus!');
    }

    public function storeActivityRule(Request $request)
    {
        $validated = $request->validate([
            'activity_type' => 'required|string',
            'weather_field' => 'required|string',
            'operator' => 'required|string',
            'threshold' => 'required|numeric',
            'action' => 'required|string',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        \App\Models\ActivityWeatherRule::create($validated);
        return redirect()->back()->with('success', 'Aturan Modifikasi berhasil dibuat!');
    }

    public function updateActivityRule(Request $request, \App\Models\ActivityWeatherRule $activityRule)
    {
        $validated = $request->validate([
            'activity_type' => 'required|string',
            'weather_field' => 'required|string',
            'operator' => 'required|string',
            'threshold' => 'required|numeric',
            'action' => 'required|string',
            'message' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $activityRule->update($validated);
        return redirect()->back()->with('success', 'Aturan Modifikasi berhasil diperbarui!');
    }

    public function destroyActivityRule(\App\Models\ActivityWeatherRule $activityRule)
    {
        $activityRule->delete();
        return redirect()->back()->with('success', 'Aturan Modifikasi berhasil dihapus!');
    }
}
