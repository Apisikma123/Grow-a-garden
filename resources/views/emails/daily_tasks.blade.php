<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tugas Perawatan Kebun</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; background-color: #f4f7f6; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; border-top: 4px solid #006c49;">
        <h2 style="color: #006c49; margin-top: 0;">Halo, {{ $user->name }}! 🌱</h2>
        <p>Ada <strong>{{ $totalTasks }} tugas</strong> perawatan kebun yang perlu kamu selesaikan hari ini agar tanamanmu tumbuh subur.</p>
        
        @foreach($tasksByGarden as $gardenName => $tasks)
            <div style="margin-top: 20px; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
                <div style="background-color: #f8fafc; padding: 10px 15px; border-bottom: 1px solid #e2e8f0;">
                    <h3 style="margin: 0; color: #1b6b51; font-size: 16px;">🏡 {{ $gardenName }}</h3>
                </div>
                <ul style="list-style-type: none; padding: 0; margin: 0;">
                    @foreach($tasks as $task)
                        <li style="padding: 10px 15px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center;">
                            <span style="font-size: 18px; margin-right: 10px;">
                                @if($task['type'] === 'water') 💧
                                @elseif($task['type'] === 'fertilizer') 🌿
                                @else ✂️ @endif
                            </span>
                            <div>
                                <strong style="display: block; color: #334155;">{{ $task['plant_name'] }}</strong>
                                <span style="font-size: 13px; color: #64748b;">
                                    @if($task['type'] === 'water')
                                        Siram tanaman ({{ $task['amount'] ?? 'secukupnya' }})
                                    @elseif($task['type'] === 'fertilizer')
                                        Beri pupuk
                                    @else
                                        Pangkas/Rawat tanaman
                                    @endif
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url('/tasks') }}" style="display: inline-block; background-color: #006c49; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
                Lihat & Kerjakan Tugas
            </a>
        </div>
        
        <p style="margin-top: 30px; font-size: 12px; color: #94a3b8; text-align: center;">
            Jika kamu tidak ingin menerima email ini, kamu bisa mengubah pengaturannya di profil akunmu.<br>
            Grow-a-Garden &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
