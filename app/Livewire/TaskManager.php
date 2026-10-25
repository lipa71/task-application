<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public ?int $selectedProjectId = null;

    public string $selectedStatus = '';
    public string $searchTask = '';
    public int $perPage = 20;

    public string $dateFrom = '';
    public string $dateTo = '';
    public string $sortDirection = 'asc';

    public function mount(): void
    {
        if (request()->query('project_id')) {
            $this->selectedProjectId = (int) request()->query('project_id');
        }
    }

    public function updatedSelectedProjectId(): void
    {
        $this->reset('selectedStatus', 'searchTask', 'dateFrom', 'dateTo', 'sortDirection');
        $this->resetPage();
    }

    public function updatedSelectedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSearchTask(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            $this->dateFrom = '';
            session()->flash('error', 'The Start Date cannot be later than the End Date.');
        }
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        if ($this->dateFrom && $this->dateTo && $this->dateTo < $this->dateFrom) {
            $this->dateTo = '';
            session()->flash('error', 'The End Date cannot be earlier than the Start Date.');
        }
        $this->resetPage();
    }

    public function toggleDateSort(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function deleteTask(int $id): void
    {
        $task = Task::where('id', $id)
            ->where('project_id', $this->selectedProjectId)
            ->first();

        if ($task) {
            $task->delete();
            $this->updateProjectPriorities();
            session()->flash('success', 'Task deleted successfully.');
        }
    }

    public function reorderTasks(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Task::where('id', $id)
                ->where('project_id', $this->selectedProjectId)
                ->update(['priority' => $index + 1]);
        }

        session()->flash('success', 'Tasks reordered successfully!');
    }

    private function updateProjectPriorities(): void
    {
        $tasks = Task::where('project_id', $this->selectedProjectId)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($tasks as $index => $task) {
            $task->update(['priority' => $index + 1]);
        }
    }

    public function render()
    {
        $projects = Project::withCount('tasks')->get();

        $tasks = $this->selectedProjectId
            ? Task::where('project_id', $this->selectedProjectId)
                ->when($this->selectedStatus, function ($query) {
                    return $query->where('status', $this->selectedStatus);
                })
                ->when($this->searchTask, function ($query) {
                    return $query->where('name', 'like', '%' . $this->searchTask . '%');
                })
                ->when($this->dateFrom, function ($query) {
                    return $query->whereDate('created_at', '>=', $this->dateFrom);
                })
                ->when($this->dateTo, function ($query) {
                    return $query->whereDate('created_at', '<=', $this->dateTo);
                })
                ->orderBy('created_at', $this->sortDirection)
                ->paginate($this->perPage)
            : collect();

        return view('livewire.task-manager', [
            'projects' => $projects,
            'tasks' => $tasks
        ])->layout('components.layouts.app');
    }
}
