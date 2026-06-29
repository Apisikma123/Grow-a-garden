<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\TemplateStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates.
     */
    public function index()
    {
        $templates = Template::with('stages')->get();
        return view('admin.care-templates', compact('templates'));
    }

    /**
     * Store a newly created template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'duration_min' => 'required|integer|min:0',
            'duration_max' => 'required|integer|gte:duration_min',
            'image' => 'nullable|string|max:2048',
            'stages' => 'required|array|min:1',
            'stages.*.stage_name' => 'required|string|max:255',
            'stages.*.start_day' => 'required|integer|min:0',
            'stages.*.end_day' => 'required|integer',
            'stages.*.icon' => 'nullable|string|max:255',
        ]);

        $stages = $request->input('stages', []);
        
        // Custom validations
        $prevEnd = -1;
        foreach ($stages as $index => $stage) {
            $start = intval($stage['start_day']);
            $end = intval($stage['end_day']);
            
            if ($end <= $start) {
                return response()->json([
                    'message' => "The given data was invalid.",
                    'errors' => [
                        "stages.{$index}.end_day" => ["Stage '" . ($stage['stage_name'] ?: ($index+1)) . "' End Day must be greater than Start Day."]
                    ]
                ], 422);
            }
            
            if ($start < $prevEnd) {
                return response()->json([
                    'message' => "The given data was invalid.",
                    'errors' => [
                        "stages.{$index}.start_day" => ["Stage '" . ($stage['stage_name'] ?: ($index+1)) . "' Start Day must be at least the End Day of the previous stage."]
                    ]
                ], 422);
            }
            
            $prevEnd = $end;
        }

        DB::beginTransaction();
        try {
            $template = Template::create([
                'name' => $request->name,
                'category' => $request->category,
                'duration_min' => $request->duration_min,
                'duration_max' => $request->duration_max,
                'image' => $request->image ?: 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop',
            ]);

            foreach ($stages as $index => $stage) {
                TemplateStage::create([
                    'template_id' => $template->id,
                    'stage_order' => $index + 1,
                    'stage_name' => $stage['stage_name'],
                    'start_day' => $stage['start_day'],
                    'end_day' => $stage['end_day'],
                    'icon' => $stage['icon'] ?: 'eco',
                ]);
            }

            DB::commit();

            $html = view('admin.partials.template-grid', ['templates' => Template::with('stages')->get()])->render();

            return response()->json([
                'success' => true,
                'message' => 'Template created successfully!',
                'html' => $html
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified template.
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'duration_min' => 'required|integer|min:0',
            'duration_max' => 'required|integer|gte:duration_min',
            'image' => 'nullable|string|max:2048',
            'stages' => 'required|array|min:1',
            'stages.*.stage_name' => 'required|string|max:255',
            'stages.*.start_day' => 'required|integer|min:0',
            'stages.*.end_day' => 'required|integer',
            'stages.*.icon' => 'nullable|string|max:255',
        ]);

        $stages = $request->input('stages', []);
        
        // Custom validations
        $prevEnd = -1;
        foreach ($stages as $index => $stage) {
            $start = intval($stage['start_day']);
            $end = intval($stage['end_day']);
            
            if ($end <= $start) {
                return response()->json([
                    'message' => "The given data was invalid.",
                    'errors' => [
                        "stages.{$index}.end_day" => ["Stage '" . ($stage['stage_name'] ?: ($index+1)) . "' End Day must be greater than Start Day."]
                    ]
                ], 422);
            }
            
            if ($start < $prevEnd) {
                return response()->json([
                    'message' => "The given data was invalid.",
                    'errors' => [
                        "stages.{$index}.start_day" => ["Stage '" . ($stage['stage_name'] ?: ($index+1)) . "' Start Day must be at least the End Day of the previous stage."]
                    ]
                ], 422);
            }
            
            $prevEnd = $end;
        }

        DB::beginTransaction();
        try {
            $template->update([
                'name' => $request->name,
                'category' => $request->category,
                'duration_min' => $request->duration_min,
                'duration_max' => $request->duration_max,
                'image' => $request->image ?: 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop',
            ]);

            // Sync stages: delete old ones and create new
            $template->stages()->delete();

            foreach ($stages as $index => $stage) {
                TemplateStage::create([
                    'template_id' => $template->id,
                    'stage_order' => $index + 1,
                    'stage_name' => $stage['stage_name'],
                    'start_day' => $stage['start_day'],
                    'end_day' => $stage['end_day'],
                    'icon' => $stage['icon'] ?: 'eco',
                ]);
            }

            DB::commit();

            $html = view('admin.partials.template-grid', ['templates' => Template::with('stages')->get()])->render();

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully!',
                'html' => $html
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate the specified template.
     */
    public function duplicate(Template $template)
    {
        DB::beginTransaction();
        try {
            $newTemplate = Template::create([
                'name' => 'Copy of ' . $template->name,
                'category' => $template->category,
                'duration_min' => $template->duration_min,
                'duration_max' => $template->duration_max,
                'image' => $template->image,
            ]);

            foreach ($template->stages as $stage) {
                TemplateStage::create([
                    'template_id' => $newTemplate->id,
                    'stage_order' => $stage->stage_order,
                    'stage_name' => $stage->stage_name,
                    'start_day' => $stage->start_day,
                    'end_day' => $stage->end_day,
                    'icon' => $stage->icon,
                ]);
            }

            DB::commit();

            $html = view('admin.partials.template-grid', ['templates' => Template::with('stages')->get()])->render();

            return response()->json([
                'success' => true,
                'message' => 'Template duplicated successfully!',
                'html' => $html
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified template.
     */
    public function destroy(Template $template)
    {
        try {
            $template->delete(); // Cascades deletes to stages because of constraints

            $html = view('admin.partials.template-grid', ['templates' => Template::with('stages')->get()])->render();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully!',
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }
}
