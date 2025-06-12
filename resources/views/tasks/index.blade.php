@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">🗂️ Task Manager</h2>

    {{-- Add Task --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">Add New Task</div>
        <div class="card-body">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="taskName" class="form-label">Task Name</label>
                    <input id="taskName" name="name" class="form-control" placeholder="e.g. Fix bug #42" required>
                </div>
                <div class="mb-3">
                    <label for="projectSelect" class="form-label">Select Project</label>
                    <select name="project_id" id="projectSelect" class="form-select" required>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $project->id == $currentProject ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary w-100">➕ Add Task</button>
            </form>
        </div>
    </div>

    {{-- Add Project --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white">Add New Project</div>
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input name="name" class="form-control" placeholder="e.g. Website Redesign" required>
                    <button class="btn btn-secondary">➕ Add Project</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Task List --}}
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">Tasks (Drag to Reorder)</div>
        <ul id="task-list" class="list-group list-group-flush">
            @forelse ($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="d-flex flex-grow-1 me-2">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $task->name }}" class="form-control me-2" required>
                        <button class="btn btn-sm btn-success">💾</button>
                    </form>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item text-center">No tasks found for this project.</li>
            @endforelse
        </ul>
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
@endsection
