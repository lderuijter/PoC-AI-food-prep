<x-layouts.app>
    <h1 class="text-2xl font-semibold text-gray-100 mb-6">Ingrediënten</h1>

    <div class="space-y-3">
        @forelse ($ingredienten as $ingredient)
            <div class="bg-gray-800 p-4 rounded shadow flex justify-between items-start">
                <div>
                    <h2 class="text-gray-100 font-semibold">{{ $ingredient->naam }}</h2>
                    <p class="text-gray-400 text-sm">
                        {{ $ingredient->categorie }} · {{ $ingredient->calorieen_per_100g }} kcal ·
                        E {{ $ingredient->eiwitten_per_100g }}g · KH {{ $ingredient->koolhydraten_per_100g }}g ·
                        V {{ $ingredient->vetten_per_100g }}g · NOVA {{ $ingredient->nova_groep }}
                    </p>
                </div>
                <form action="{{ route('voorkeuren.store', $ingredient) }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="voorkeur" class="bg-gray-700 text-gray-100 border border-gray-600 rounded px-2 py-1 w-16">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white text-sm px-3 py-1 rounded">Voorkeur</button>
                </form>
            </div>
        @empty
            <p class="text-gray-500">Nog geen ingrediënten.</p>
        @endforelse
    </div>
</x-layouts.app>
