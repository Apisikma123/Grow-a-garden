<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // ORIGINAL 6 BADGES (Preserved so existing users don't lose them)
            ['name' => 'Tangan Dingin', 'description' => 'Selesaikan 5 tugas perawatan kebun secara total.', 'icon_url' => 'military_tech'],
            ['name' => 'Penyiram Handal', 'description' => 'Selesaikan 10 tugas penyiraman tanaman.', 'icon_url' => 'water_drop'],
            ['name' => 'Pakar Pemupuk', 'description' => 'Selesaikan 5 kali pemupukan nutrisi.', 'icon_url' => 'compost'],
            ['name' => 'Pekebun Pertama', 'description' => 'Memiliki kebun aktif pertama Anda.', 'icon_url' => 'yard'],
            ['name' => 'Pembasmi Hama', 'description' => 'Selesaikan 3 tugas pengontrolan hama.', 'icon_url' => 'bug_report'],
            ['name' => 'Panen Raya', 'description' => 'Berhasil memanen tanaman pertama Anda.', 'icon_url' => 'local_florist'],

            // ==========================================
            // WATERING BADGES (15)
            // ==========================================
            ['name' => 'Setetes Air', 'description' => 'Selesaikan 1 tugas penyiraman.', 'icon_url' => 'opacity'],
            ['name' => 'Menyiram 5', 'description' => 'Selesaikan 5 tugas penyiraman.', 'icon_url' => 'water_drop'],
            ['name' => 'Menyiram 25', 'description' => 'Selesaikan 25 tugas penyiraman.', 'icon_url' => 'rainy'],
            ['name' => 'Menyiram 50', 'description' => 'Selesaikan 50 tugas penyiraman.', 'icon_url' => 'water'],
            ['name' => 'Menyiram 100', 'description' => 'Selesaikan 100 tugas penyiraman.', 'icon_url' => 'waves'],
            ['name' => 'Menyiram 250', 'description' => 'Selesaikan 250 tugas penyiraman.', 'icon_url' => 'sailing'],
            ['name' => 'Menyiram 500', 'description' => 'Selesaikan 500 tugas penyiraman.', 'icon_url' => 'pool'],
            ['name' => 'Menyiram 750', 'description' => 'Selesaikan 750 tugas penyiraman.', 'icon_url' => 'tsunami'],
            ['name' => 'Menyiram 1.000', 'description' => 'Selesaikan 1.000 tugas penyiraman.', 'icon_url' => 'flood'],
            ['name' => 'Menyiram 1.500', 'description' => 'Selesaikan 1.500 tugas penyiraman.', 'icon_url' => 'shower'],
            ['name' => 'Menyiram 2.000', 'description' => 'Selesaikan 2.000 tugas penyiraman.', 'icon_url' => 'bathtub'],
            ['name' => 'Menyiram 3.000', 'description' => 'Selesaikan 3.000 tugas penyiraman.', 'icon_url' => 'wash'],
            ['name' => 'Menyiram 5.000', 'description' => 'Selesaikan 5.000 tugas penyiraman.', 'icon_url' => 'clean_hands'],
            ['name' => 'Menyiram 7.500', 'description' => 'Selesaikan 7.500 tugas penyiraman.', 'icon_url' => 'sanitizer'],
            ['name' => 'Menyiram 10.000', 'description' => 'Selesaikan 10.000 tugas penyiraman.', 'icon_url' => 'cruelty_free'],

            // ==========================================
            // FERTILIZING BADGES (12)
            // ==========================================
            ['name' => 'Pupuk Pertama', 'description' => 'Selesaikan 1 tugas pemupukan.', 'icon_url' => 'eco'],
            ['name' => 'Pemupukan 10', 'description' => 'Selesaikan 10 tugas pemupukan.', 'icon_url' => 'recycling'],
            ['name' => 'Pemupukan 25', 'description' => 'Selesaikan 25 tugas pemupukan.', 'icon_url' => 'science'],
            ['name' => 'Pemupukan 50', 'description' => 'Selesaikan 50 tugas pemupukan.', 'icon_url' => 'spa'],
            ['name' => 'Pemupukan 100', 'description' => 'Selesaikan 100 tugas pemupukan.', 'icon_url' => 'agriculture'],
            ['name' => 'Pemupukan 200', 'description' => 'Selesaikan 200 tugas pemupukan.', 'icon_url' => 'medication_liquid'],
            ['name' => 'Pemupukan 300', 'description' => 'Selesaikan 300 tugas pemupukan.', 'icon_url' => 'experiment'],
            ['name' => 'Pemupukan 500', 'description' => 'Selesaikan 500 tugas pemupukan.', 'icon_url' => 'biotech'],
            ['name' => 'Pemupukan 1.000', 'description' => 'Selesaikan 1.000 tugas pemupukan.', 'icon_url' => 'vaccines'],
            ['name' => 'Pemupukan 2.000', 'description' => 'Selesaikan 2.000 tugas pemupukan.', 'icon_url' => 'bloodtype'],
            ['name' => 'Pemupukan 5.000', 'description' => 'Selesaikan 5.000 tugas pemupukan.', 'icon_url' => 'auto_fix_high'],
            ['name' => 'Pemupukan 10.000', 'description' => 'Selesaikan 10.000 tugas pemupukan.', 'icon_url' => 'diamond'],

            // ==========================================
            // PRUNING BADGES (11)
            // ==========================================
            ['name' => 'Potongan Pertama', 'description' => 'Selesaikan 1 tugas pemangkasan.', 'icon_url' => 'content_cut'],
            ['name' => 'Pemangkasan 5', 'description' => 'Selesaikan 5 tugas pemangkasan.', 'icon_url' => 'content_cut'],
            ['name' => 'Pemangkasan 10', 'description' => 'Selesaikan 10 tugas pemangkasan.', 'icon_url' => 'design_services'],
            ['name' => 'Pemangkasan 25', 'description' => 'Selesaikan 25 tugas pemangkasan.', 'icon_url' => 'brush'],
            ['name' => 'Pemangkasan 50', 'description' => 'Selesaikan 50 tugas pemangkasan.', 'icon_url' => 'cleaning_services'],
            ['name' => 'Pemangkasan 100', 'description' => 'Selesaikan 100 tugas pemangkasan.', 'icon_url' => 'mop'],
            ['name' => 'Pemangkasan 200', 'description' => 'Selesaikan 200 tugas pemangkasan.', 'icon_url' => 'delete_sweep'],
            ['name' => 'Pemangkasan 300', 'description' => 'Selesaikan 300 tugas pemangkasan.', 'icon_url' => 'dry_cleaning'],
            ['name' => 'Pemangkasan 500', 'description' => 'Selesaikan 500 tugas pemangkasan.', 'icon_url' => 'cut'],
            ['name' => 'Pemangkasan 1.000', 'description' => 'Selesaikan 1.000 tugas pemangkasan.', 'icon_url' => 'content_copy'],
            ['name' => 'Pemangkasan 5.000', 'description' => 'Selesaikan 5.000 tugas pemangkasan.', 'icon_url' => 'styler'],

            // ==========================================
            // PEST CONTROL BADGES (11)
            // ==========================================
            ['name' => 'Hama Pertama', 'description' => 'Selesaikan 1 tugas pembasmian hama.', 'icon_url' => 'coronavirus'],
            ['name' => 'Pembasmi 5', 'description' => 'Selesaikan 5 tugas pembasmian hama.', 'icon_url' => 'pest_control'],
            ['name' => 'Pembasmi 10', 'description' => 'Selesaikan 10 tugas pembasmian hama.', 'icon_url' => 'shield'],
            ['name' => 'Pembasmi 25', 'description' => 'Selesaikan 25 tugas pembasmian hama.', 'icon_url' => 'admin_panel_settings'],
            ['name' => 'Pembasmi 50', 'description' => 'Selesaikan 50 tugas pembasmian hama.', 'icon_url' => 'health_and_safety'],
            ['name' => 'Pembasmi 100', 'description' => 'Selesaikan 100 tugas pembasmian hama.', 'icon_url' => 'local_hospital'],
            ['name' => 'Pembasmi 200', 'description' => 'Selesaikan 200 tugas pembasmian hama.', 'icon_url' => 'healing'],
            ['name' => 'Pembasmi 300', 'description' => 'Selesaikan 300 tugas pembasmian hama.', 'icon_url' => 'search'],
            ['name' => 'Pembasmi 500', 'description' => 'Selesaikan 500 tugas pembasmian hama.', 'icon_url' => 'visibility'],
            ['name' => 'Pembasmi 1.000', 'description' => 'Selesaikan 1.000 tugas pembasmian hama.', 'icon_url' => 'radar'],
            ['name' => 'Pembasmi 5.000', 'description' => 'Selesaikan 5.000 tugas pembasmian hama.', 'icon_url' => 'security'],

            // ==========================================
            // HARVESTING BADGES (11)
            // ==========================================
            ['name' => 'Panen 5', 'description' => 'Selesaikan 5 tugas panen.', 'icon_url' => 'shopping_basket'],
            ['name' => 'Panen 10', 'description' => 'Selesaikan 10 tugas panen.', 'icon_url' => 'shopping_cart'],
            ['name' => 'Panen 25', 'description' => 'Selesaikan 25 tugas panen.', 'icon_url' => 'storefront'],
            ['name' => 'Panen 50', 'description' => 'Selesaikan 50 tugas panen.', 'icon_url' => 'domain'],
            ['name' => 'Panen 100', 'description' => 'Selesaikan 100 tugas panen.', 'icon_url' => 'celebration'],
            ['name' => 'Panen 200', 'description' => 'Selesaikan 200 tugas panen.', 'icon_url' => 'stars'],
            ['name' => 'Panen 300', 'description' => 'Selesaikan 300 tugas panen.', 'icon_url' => 'redeem'],
            ['name' => 'Panen 500', 'description' => 'Selesaikan 500 tugas panen.', 'icon_url' => 'card_giftcard'],
            ['name' => 'Panen 1.000', 'description' => 'Selesaikan 1.000 tugas panen.', 'icon_url' => 'inventory'],
            ['name' => 'Panen 2.000', 'description' => 'Selesaikan 2.000 tugas panen.', 'icon_url' => 'widgets'],
            ['name' => 'Panen 5.000', 'description' => 'Selesaikan 5.000 tugas panen.', 'icon_url' => 'category'],

            // ==========================================
            // TOTAL TASKS (15)
            // ==========================================
            ['name' => 'Tugas 10', 'description' => 'Selesaikan 10 tugas perawatan apa saja.', 'icon_url' => 'looks_one'],
            ['name' => 'Tugas 25', 'description' => 'Selesaikan 25 tugas perawatan apa saja.', 'icon_url' => 'looks_two'],
            ['name' => 'Tugas 50', 'description' => 'Selesaikan 50 tugas perawatan apa saja.', 'icon_url' => 'looks_3'],
            ['name' => 'Tugas 100', 'description' => 'Selesaikan 100 tugas perawatan apa saja.', 'icon_url' => 'looks_4'],
            ['name' => 'Tugas 250', 'description' => 'Selesaikan 250 tugas perawatan apa saja.', 'icon_url' => 'looks_5'],
            ['name' => 'Tugas 500', 'description' => 'Selesaikan 500 tugas perawatan apa saja.', 'icon_url' => 'looks_6'],
            ['name' => 'Tugas 1.000', 'description' => 'Selesaikan 1.000 tugas perawatan apa saja.', 'icon_url' => 'work'],
            ['name' => 'Tugas 2.500', 'description' => 'Selesaikan 2.500 tugas perawatan apa saja.', 'icon_url' => 'work_history'],
            ['name' => 'Tugas 5.000', 'description' => 'Selesaikan 5.000 tugas perawatan apa saja.', 'icon_url' => 'assignment_turned_in'],
            ['name' => 'Tugas 7.500', 'description' => 'Selesaikan 7.500 tugas perawatan apa saja.', 'icon_url' => 'task_alt'],
            ['name' => 'Tugas 10.000', 'description' => 'Selesaikan 10.000 tugas perawatan apa saja.', 'icon_url' => 'done_all'],
            ['name' => 'Tugas 25.000', 'description' => 'Selesaikan 25.000 tugas perawatan apa saja.', 'icon_url' => 'fact_check'],
            ['name' => 'Tugas 50.000', 'description' => 'Selesaikan 50.000 tugas perawatan apa saja.', 'icon_url' => 'verified'],
            ['name' => 'Tugas 75.000', 'description' => 'Selesaikan 75.000 tugas perawatan apa saja.', 'icon_url' => 'workspace_premium'],
            ['name' => 'Tugas 100.000', 'description' => 'Selesaikan 100.000 tugas perawatan apa saja.', 'icon_url' => 'social_leaderboard'],

            // ==========================================
            // SKIPPED TASKS (5)
            // ==========================================
            ['name' => 'Santai Dulu', 'description' => 'Lewati (Skip) 1 tugas perawatan.', 'icon_url' => 'snooze'],
            ['name' => 'Kaum Rebahan', 'description' => 'Lewati (Skip) 5 tugas perawatan.', 'icon_url' => 'bedtime'],
            ['name' => 'Cuti Berkebun', 'description' => 'Lewati (Skip) 10 tugas perawatan.', 'icon_url' => 'hotel'],
            ['name' => 'Kebun Terlantar', 'description' => 'Lewati (Skip) 50 tugas perawatan.', 'icon_url' => 'hourglass_empty'],
            ['name' => 'Hanya Mengamati', 'description' => 'Lewati (Skip) 100 tugas perawatan.', 'icon_url' => 'visibility'],

            // ==========================================
            // GARDENS CREATED (4)
            // ==========================================
            ['name' => 'Kebun Kedua', 'description' => 'Memiliki 2 kebun.', 'icon_url' => 'park'],
            ['name' => 'Kebun Ketiga', 'description' => 'Memiliki 3 kebun.', 'icon_url' => 'nature_people'],
            ['name' => 'Kebun Keempat', 'description' => 'Memiliki 4 kebun.', 'icon_url' => 'forest'],
            ['name' => 'Master Ekosistem', 'description' => 'Memiliki 5 kebun.', 'icon_url' => 'public'],

            // ==========================================
            // PLANTS ADDED (9)
            // ==========================================
            ['name' => 'Tanaman Pertama', 'description' => 'Menambahkan tanaman pertama ke kebun.', 'icon_url' => 'potted_plant'],
            ['name' => 'Tanaman 5', 'description' => 'Menambahkan 5 tanaman.', 'icon_url' => 'local_florist'],
            ['name' => 'Tanaman 10', 'description' => 'Menambahkan 10 tanaman.', 'icon_url' => 'grass'],
            ['name' => 'Tanaman 20', 'description' => 'Menambahkan 20 tanaman.', 'icon_url' => 'emoji_nature'],
            ['name' => 'Tanaman 50', 'description' => 'Menambahkan 50 tanaman.', 'icon_url' => 'view_comfy'],
            ['name' => 'Tanaman 100', 'description' => 'Menambahkan 100 tanaman.', 'icon_url' => 'travel_explore'],
            ['name' => 'Tanaman 200', 'description' => 'Menambahkan 200 tanaman.', 'icon_url' => 'nature'],
            ['name' => 'Tanaman 500', 'description' => 'Menambahkan 500 tanaman.', 'icon_url' => 'landscape'],
            ['name' => 'Tanaman 1.000', 'description' => 'Menambahkan 1.000 tanaman.', 'icon_url' => 'account_tree'],

            // ==========================================
            // APP FEATURES & SUBSCRIPTION (1)
            // ==========================================
            ['name' => 'Sang Pro', 'description' => 'Berlangganan paket Grow a Garden Pro.', 'icon_url' => 'star'],
        ];

        foreach ($badges as $badge) {
            $target = 1;
            $cleanDesc = str_replace('.', '', $badge['description'] ?? '');
            if (preg_match('/(\d+)/', $cleanDesc, $matches)) {
                $target = (int) $matches[1];
            }
            if ($target <= 0) $target = 1;

            $name = strtolower($badge['name'] ?? '');
            $desc = strtolower($badge['description'] ?? '');
            $metricType = 'total_tasks';

            if (str_contains($name, 'sang pro') || str_contains($desc, 'subur (pro)') || str_contains($desc, 'grow a garden pro')) {
                $metricType = 'pro';
            } elseif (str_contains($name, 'panen raya premium') || str_contains($name, 'pekebun panen raya') || str_contains($desc, 'panen raya (premium)')) {
                $metricType = 'premium';
            } elseif (str_contains($name, 'siram') || str_contains($name, 'water') || str_contains($name, 'setetes') || str_contains($desc, 'penyiraman')) {
                $metricType = 'watering';
            } elseif (str_contains($name, 'pupuk') || str_contains($name, 'pemupukan') || str_contains($desc, 'pemupukan')) {
                $metricType = 'fertilizing';
            } elseif (str_contains($name, 'pangkas') || str_contains($name, 'pemangkasan') || str_contains($name, 'potongan') || str_contains($desc, 'pemangkasan')) {
                $metricType = 'pruning';
            } elseif (str_contains($name, 'hama') || str_contains($name, 'pembasmi') || str_contains($desc, 'pembasmian') || (str_contains($desc, 'hama') && !str_contains($name, 'kebun'))) {
                $metricType = 'pest';
            } elseif (str_contains($name, 'panen') || str_contains($desc, 'panen')) {
                $metricType = 'harvest';
            } elseif (str_contains($name, 'santai') || str_contains($name, 'rebahan') || str_contains($name, 'cuti') || str_contains($name, 'terlantar') || str_contains($name, 'mengamati') || str_contains($desc, 'lewati (skip)')) {
                $metricType = 'skipped';
            } elseif (str_contains($name, 'tanaman') || str_contains($desc, 'menambahkan') || str_contains($desc, 'menanam')) {
                $metricType = 'plants';
            } elseif (str_contains($name, 'kebun') || str_contains($name, 'pekebun') || str_contains($name, 'ekosistem') || (str_contains($desc, 'kebun') && !str_contains($desc, 'ke kebun'))) {
                $metricType = 'gardens';
            } elseif (str_contains($name, 'langkah') || str_contains($desc, 'menyelesaikan tugas')) {
                $metricType = 'total_tasks';
            }

            Badge::updateOrCreate(
                ['name' => $badge['name']],
                [
                    'description' => $badge['description'],
                    'icon_url' => $badge['icon_url'],
                    'metric_type' => $metricType,
                    'target_count' => $target,
                ]
            );
        }
    }
}
