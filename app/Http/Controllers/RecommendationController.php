<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Process;

class RecommendationController extends Controller
{
    private const KLEUREN = [
        'groen' => [
            'border' => 'border-green-500',
            'dot' => 'bg-green-500',
        ],
        'geel' => [
            'border' => 'border-yellow-500',
            'dot' => 'bg-yellow-500',
        ],
        'oranje' => [
            'border' => 'border-orange-500',
            'dot' => 'bg-orange-500',
        ],
        'rood' => [
            'border' => 'border-red-500',
            'dot' => 'bg-red-500',
        ],
    ];

    public function index()
    {
        $userId = 1; // vaste test-user zolang er geen login is

        $voorkeuren = Ingredient::whereHas('gebruikers', fn ($q) => $q->where('user_id', $userId))
            ->with(['gebruikers' => fn ($q) => $q->where('user_id', $userId)])
            ->get()
            ->map(fn ($ingredient) => [
                'naam' => $ingredient->naam,
                'voorkeur' => $ingredient->gebruikers->first()->pivot->voorkeur,
            ])
            ->sortByDesc('voorkeur')
            ->values();

        $pythonPath = base_path('python/.venv/Scripts/python.exe');
        $scriptPath = base_path('python/recommend.py');

        $result = Process::env(getenv())
            ->run([$pythonPath, $scriptPath, $userId]);

        if ($result->failed()) {
            return back()->withErrors(['recommend' => $result->errorOutput()]);
        }

        $ruweData = json_decode($result->output(), true) ?? [];

        if (isset($ruweData['error'])) {
            return view('aanbevelingen.index', [
                'aanbevelingen' => collect(),
                'voorkeuren' => $voorkeuren,
                'foutmelding' => $ruweData['error'],
            ]);
        }

        $aanbevelingen = collect($ruweData)->map(function (array $item) {
            $item['match_klasse'] = self::KLEUREN[$item['match_kleur']]['border'] ?? 'border-gray-600';
            $item['nova_klasse'] = self::KLEUREN[$item['nova_kleur']]['dot'] ?? 'bg-gray-400';
            return $item;
        });

        return view('aanbevelingen.index', ['aanbevelingen' => $aanbevelingen, 'voorkeuren' => $voorkeuren, 'foutmelding' => null]);
    }
}
