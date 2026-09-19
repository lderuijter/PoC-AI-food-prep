<x-layouts.app>
    <flux:heading size="xl" class="text-gray-900 font-semibold mb-6">Todos</flux:heading>

    <form action="{{ route('todos.store') }}" method="POST" class="bg-white p-4 rounded shadow mb-6 space-y-4">
        @csrf
        <flux:input name="title" placeholder="Title" />
        <flux:textarea name="description" placeholder="Description" />
        <flux:button type="submit" variant="primary">Add Todo</flux:button>
    </form>

    <div class="space-y-3">
        @forelse ($todos as $todo)
            <div class="bg-white p-4 rounded shadow flex justify-between items-start">
                <div>
                    <h2 class="text-gray-900 font-semibold">{{ $todo->title }}</h2>
                    <p class="text-gray-600 text-sm">{{ $todo->description }}</p>
                </div>
                <div class="flex gap-2 items-center">
                    <flux:button href="{{ route('todos.edit', $todo) }}" size="sm" variant="subtle">Edit</flux:button>
                    <form action="{{ route('todos.destroy', $todo) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <flux:button type="submit" size="sm" variant="danger">Delete</flux:button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No todos yet.</p>
        @endforelse
    </div>
</x-layouts.app>
