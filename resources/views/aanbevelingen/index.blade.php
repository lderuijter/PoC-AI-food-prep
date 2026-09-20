<x-layouts.app>
    <a href="{{ route('ingredienten.index') }}" class="inline-block text-gray-400 hover:text-gray-200 text-sm mb-6">←
        Terug</a>

    <h1 class="text-2xl font-semibold text-gray-100 mb-6">Aanbevolen recepten</h1>

    @if ($foutmelding)
        <p class="text-gray-500">{{ $foutmelding }}</p>
    @else
        <div class="space-y-3">
            @forelse ($aanbevelingen as $item)
                <div class="bg-gray-800 p-4 rounded shadow border {{ $item['match_klasse'] }}">
                    <h2 class="text-gray-100 font-semibold">{{ $item['naam'] }}</h2>
                    <p class="text-sm">
                        <span class="text-gray-100 font-semibold">
                            {{ $item['match_percentage'] }}% match
                        </span>
                        <span class="text-gray-400 inline-flex items-center gap-1">
                            NOVA: {{ round($item['gemiddelde_nova']) }}
                            <span class="w-2 h-2 rounded-full {{ $item['nova_klasse'] }}">
                                <!-- DOT -->
                            </span>
                        </span>
                    </p>
                </div>
            @empty
                <p class="text-gray-500">Geen relevante aanbevelingen, geef eerst meer voorkeuren op.</p>
            @endforelse
        </div>
    @endif
</x-layouts.app>
