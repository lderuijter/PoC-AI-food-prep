<x-layouts.app>
    <flux:heading size="xl" class="text-gray-900 font-semibold mb-6">Edit Todo</flux:heading>

    <form action="{{ route('todos.update', $todo) }}" method="POST" class="bg-white p-4 rounded shadow space-y-4">
        @csrf
        @method('PUT')
        <flux:input name="title" value="{{ $todo->title }}" />
        <flux:textarea name="description">{{ $todo->description }}</flux:textarea>
        <flux:button type="submit" variant="primary">Update Todo</flux:button>
    </form>

    <flux:button href="{{ route('todos.index') }}" variant="subtle" class="text-gray-900 font-semibold mt-4">← Back</flux:button>
</x-layouts.app>
