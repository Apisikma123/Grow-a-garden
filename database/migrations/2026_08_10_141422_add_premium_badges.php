<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add premium/pro subscription badges.
     */
    public function up(): void
    {
        $now = now();

        $badges = [
            // Pro subscription badges
            [
                'name'        => 'Kebun Profesional',
                'description' => 'Berlangganan paket Subur (Pro) untuk pertama kali.',
                'icon_url'    => 'star',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            // Premium subscription badges
            [
                'name'        => 'Pekebun Panen Raya',
                'description' => 'Berlangganan paket Panen Raya (Premium).',
                'icon_url'    => 'workspace_premium',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Elite Grower',
                'description' => 'Upgrade ke paket Panen Raya untuk akses kebun tanpa batas.',
                'icon_url'    => 'diamond',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        // Only insert if not already present (idempotent)
        foreach ($badges as $badge) {
            $exists = DB::table('badges')->where('name', $badge['name'])->exists();
            if (!$exists) {
                DB::table('badges')->insert($badge);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('badges')->whereIn('name', [
            'Kebun Profesional',
            'Pekebun Panen Raya',
            'Elite Grower',
        ])->delete();
    }
};
