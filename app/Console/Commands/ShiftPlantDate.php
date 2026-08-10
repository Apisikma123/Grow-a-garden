<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plant;
use Carbon\Carbon;

class ShiftPlantDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'care:shift-plant {plant_id} {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Geser tanggal tanam (planted_date) untuk testing Growth Calendar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plantId = $this->argument('plant_id');
        $days = (int) $this->argument('days');

        $plant = Plant::find($plantId);
        
        if (!$plant) {
            $this->error("Tanaman dengan ID {$plantId} tidak ditemukan.");
            return;
        }

        $oldDate = Carbon::parse($plant->planted_date);
        
        if ($days < 0) {
            // Mundurkan tanggal tanam (membuat tanaman menjadi lebih tua)
            $newDate = clone $oldDate;
            $newDate->subDays(abs($days));
            $actionStr = "dimundurkan";
        } else {
            // Majukan tanggal tanam (membuat tanaman menjadi lebih muda)
            $newDate = clone $oldDate;
            $newDate->addDays($days);
            $actionStr = "dimajukan";
        }

        $plant->planted_date = $newDate;
        $plant->save();

        $this->info("Tanggal tanam {$plant->name} (ID: {$plant->id}) berhasil $actionStr sebanyak " . abs($days) . " hari.");
        $this->line("Tanggal Tanam Baru: " . $plant->planted_date->format('Y-m-d'));
        $this->line("HST (Hari Setelah Tanam) sekarang: " . $plant->planted_date->diffInDays(now()));
    }
}
