<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;

        $projects = Project::all();
        $currentProject = $request->get('project_id', $projects->first()?->id);
        $tasks = Task::where('project_id', $currentProject)
            ->where('user_id', $userId)
            ->orderBy('priority')
            ->get();

        return view('tasks.index', compact('tasks', 'projects', 'currentProject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'project_id' => 'required|exists:projects,id',
        ]);

        $priority = Task::where('project_id', $request->project_id)->max('priority') + 1;

        Task::create([
            'name' => $request->name,
            'priority' => $priority,
            'project_id' => $request->project_id,
            'user_id' => Auth::user()->id
        ]);

        return redirect()->route('tasks.index', ['project_id' => $request->project_id]);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate(['name' => 'required']);
        $task->update(['name' => $request->name]);

        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }
        return response()->json(['status' => 'ok']);
    }
}
