<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('care:generate-tasks')->dailyAt('00:00');
Schedule::command('tasks:daily-notify')->dailyAt('00:05');

Artisan::command('garden:fix-template-text', function () {
    \Illuminate\Support\Facades\DB::table('plant_templates')
        ->where('name_id', 'Bayam')
        ->update([
            'water_requirement' => '2 Liter air per m², 2x sehari (pagi & sore)',
            'sunlight' => 'Sinar Matahari Penuh (6-8 jam)'
        ]);

    \Illuminate\Support\Facades\DB::table('plant_templates')
        ->where('water_requirement', 'LIKE', '%4 L/m%')
        ->update([
            'water_requirement' => '2 Liter air per m², 2x sehari (pagi & sore)'
        ]);

    \Illuminate\Support\Facades\DB::table('plant_templates')
        ->where('sunlight', 'Full Sun')
        ->update([
            'sunlight' => 'Sinar Matahari Penuh (6-8 jam)'
        ]);

    \Illuminate\Support\Facades\DB::table('plant_templates')
        ->where('sunlight', 'Full Sun to Partial Shade')
        ->update([
            'sunlight' => 'Sinar Matahari Cukup (4-6 jam)'
        ]);

    $this->info('Plant templates text updated successfully!');
});
