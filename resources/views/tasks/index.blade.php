<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🗂️ Task Manager (Manage Your Tasks)
        </h2>
    </x-slot>

    <div class="py-10 my-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Add Task --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">➕ Add New Task</h3>
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="taskName" class="block text-sm font-medium text-gray-700">Task Name</label>
                        <input id="taskName" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g. Fix bug #42" required>
                    </div>
                    <div>
                        <label for="projectSelect" class="block text-sm font-medium text-gray-700">Select Project</label>
                        <select id="projectSelect" name="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" {{ $project->id == $currentProject ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bg-blue-600 px-4 py-2 text-white rounded hover:bg-blue-700">Add Task</button>
                </form>
            </div>

            {{-- Add Project --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">➕ Add New Project</h3>
                <form action="{{ route('projects.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input name="name" class="flex-grow border-gray-300 rounded-md shadow-sm" placeholder="e.g. Website Redesign" required>
                    <button class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">Add</button>
                </form>
            </div>

            {{-- Task List --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📋 Tasks (Drag to Reorder)</h3>
                <ul id="task-list" class="space-y-3">
                    @forelse ($tasks as $task)
                        <li class="flex items-center justify-between p-4 bg-gray-100 rounded" data-id="{{ $task->id }}">
                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="flex flex-grow items-center gap-2">
                                @csrf @method('PUT')
                                <input name="name" value="{{ $task->name }}" class="flex-grow border-gray-300 rounded-md shadow-sm" required>
                                <button class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-sm">💾</button>
                            </form>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">🗑️</button>
                            </form>
                        </li>
                    @empty
                        <li class="text-center text-gray-500">No tasks found for this project.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>

    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const list = document.getElementById('task-list');
        Sortable.create(list, {
            animation: 150,
            onEnd: () => {
                let order = [...list.children].map(li => li.dataset.id);
                fetch("{{ route('tasks.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order })
                });
            }
        });
    </script>
</x-app-layout>
