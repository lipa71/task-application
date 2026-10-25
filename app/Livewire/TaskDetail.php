<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class TaskDetail extends Component
{
    public Task $task;

    public function mount(int $id): void
    {
        $this->task = Task::with('project')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.task-detail')->layout('components.layouts.app');
    }
}
