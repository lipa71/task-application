<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;
use Illuminate\Http\Request;

class TaskForm extends Component
{
    public ?Task $task = null;
    public string $name = '';
    public string $description = '';
    public ?int $project_id = null;
    public string $status = 'in_progress';
    public bool $isEditMode = false;

    protected array $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:5000',
        'project_id' => 'required|exists:projects,id',
        'status' => 'required|in:in_progress,done,canceled',
    ];

    public function mount(Request $request, ?int $taskId = null): void
    {
        if ($taskId) {
            $this->task = Task::findOrFail($taskId);
            $this->name = $this->task->name;
            $this->description = $this->task->description ?? '';
            $this->project_id = $this->task->project_id;
            $this->status = $this->task->status;
            $this->isEditMode = true;
        } else {
            $this->project_id = $request->query('project_id');
            $this->status = 'in_progress';
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditMode) {
            $this->task->update([
                'name' => $this->name,
                'description' => $this->description,
                'project_id' => $this->project_id,
                'status' => $this->status,
            ]);
            session()->flash('success', 'Task updated successfully!');
        } else {
            $maxPriority = Task::where('project_id', $this->project_id)->max('priority') ?? 0;
            Task::create([
                'name' => $this->name,
                'description' => $this->description,
                'project_id' => $this->project_id,
                'status' => 'in_progress',
                'priority' => $maxPriority + 1,
            ]);
            session()->flash('success', 'Task created successfully.');
        }

        $this->redirect(route('tasks.index', ['project_id' => $this->project_id]), navigate: true);
    }

    public function render()
    {
        return view('livewire.task-form', [
            'projects' => Project::all()
        ])->layout('components.layouts.app');
    }
}
