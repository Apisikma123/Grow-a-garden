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

        // User Growth (Last 6 months)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyUsers = User::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(created_at) year, MONTH(created_at) month, count(*) data')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $userGrowth = [];
        $currentMonth = $sixMonthsAgo->copy();
        for ($i = 0; $i < 6; $i++) {
            $month = $currentMonth->month;
            $year = $currentMonth->year;
            $count = $monthlyUsers->where('year', $year)->where('month', $month)->first()->data ?? 0;
            $userGrowth[] = $count;
            $currentMonth->addMonth();
        }

        return view('admin.dashboard', compact(
            'totalUsers', 'totalGardens', 'totalPlants', 'premiumUsers', 'successfulHarvests',
            'popularPlants', 'topActivities', 'todayActivities', 'totalCompletedEvents', 'avgHarvestAge', 'userGrowth'
        ));
    }

    public function users()
    {
        $users = User::withCount('gardens')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function plants(Request $request)
    {
        $query = \App\Models\PlantTemplate::with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('name_id', 'like', '%'.$request->search.'%');
        }

        $plants = $query->paginate(15)->withQueryString();
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
            'harvest_end_day' => 'required|integer',
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
            'harvest_end_day' => 'required|integer',
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
            'metric_type' => 'required|string',
            'target_count' => 'required|integer|min:1',
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
            'metric_type' => 'required|string',
            'target_count' => 'required|integer|min:1',
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

    public function settings()
    {
        return view('admin.settings');
    }

    public function errorLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $lines = explode("\n", $content);
            $lines = array_reverse(array_filter($lines));
            
            foreach (array_slice($lines, 0, 30) as $line) {
                if (trim($line)) {
                    $logs[] = [
                        'timestamp' => now()->format('Y-m-d H:i:s'),
                        'message' => mb_strimwidth($line, 0, 150, '...'),
                        'level' => str_contains(strtolower($line), 'error') ? 'ERROR' : (str_contains(strtolower($line), 'warning') ? 'WARNING' : 'INFO')
                    ];
                }
            }
        }

        if (empty($logs)) {
            $logs[] = [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'message' => 'Sistem berjalan normal. Tidak ada error log kritis.',
                'level' => 'INFO'
            ];
        }

        return response()->json(['success' => true, 'logs' => $logs]);
    }

    public function activityLogs()
    {
        $events = \App\Models\Event::with(['plant.garden.user', 'eventType'])
            ->orderBy('updated_at', 'desc')
            ->take(25)
            ->get()
            ->map(function($e) {
                $userName = $e->plant->garden->user->name ?? 'Pengguna';
                $plantName = $e->plant->plantTemplate->name_id ?? 'Tanaman';
                $typeName = $e->eventType->name_id ?? $e->eventType->name ?? 'Perawatan';
                return [
                    'timestamp' => $e->updated_at ? $e->updated_at->format('d M Y, H:i') : now()->format('d M Y, H:i'),
                    'user' => $userName,
                    'action' => "{$typeName} untuk {$plantName}",
                    'status' => $e->status
                ];
            });

        return response()->json(['success' => true, 'logs' => $events]);
    }

    public function loginLogs()
    {
        $users = User::select('id', 'name', 'email', 'role', 'updated_at', 'created_at')
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get()
            ->map(function($u) {
                return [
                    'timestamp' => $u->updated_at ? $u->updated_at->format('d M Y, H:i') : now()->format('d M Y, H:i'),
                    'user' => $u->name,
                    'email' => $u->email,
                    'role' => strtoupper($u->role),
                    'ip' => '127.0.0.1 (Sesi Lokal)'
                ];
            });

        return response()->json(['success' => true, 'logs' => $users]);
    }

    public function clearErrorLogs()
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
        }
        return response()->json(['success' => true, 'message' => 'Error logs berhasil dibersihkan.']);
    }
}
