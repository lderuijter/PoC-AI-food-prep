<?php

namespace App\Http\Controllers;

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

        $result = Process::run(['python3', base_path('python/recommend.py'), $userId]);

        if ($result->failed()) {
            return back()->withErrors(['recommend' => $result->errorOutput()]);
        }

        $ruweData = json_decode($result->output(), true) ?? [];

        if (isset($ruweData['error'])) {
            return view('aanbevelingen.index', [
                'aanbevelingen' => collect(),
                'foutmelding' => $ruweData['error'],
            ]);
        }

        $aanbevelingen = collect($ruweData)->map(function (array $item) {
            $item['match_klasse'] = self::KLEUREN[$item['match_kleur']]['border'] ?? 'border-gray-600';
            $item['nova_klasse'] = self::KLEUREN[$item['nova_kleur']]['dot'] ?? 'bg-gray-400';
            return $item;
        });

        return view('aanbevelingen.index', ['aanbevelingen' => $aanbevelingen, 'foutmelding' => null]);
    }
}
