<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-start mb-4 border-b pb-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 px-2 py-1 rounded">
                {{ $task->project->name }}
            </span>
            <h1 class="text-3xl font-extrabold text-gray-900 mt-2">{{ $task->name }}</h1>
        </div>
        <a href="{{ route('tasks.index', ['project_id' => $task->project_id]) }}" wire:navigate class="text-sm font-semibold text-gray-500 hover:text-gray-700">&larr; Back to Board</a>
    </div>

    <div class="space-y-4">
        <div>
            <span class="block text-sm font-bold text-gray-700 mb-1">Status</span>
            @if($task->status == 'in_progress')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">In Progress</span>
            @elseif($task->status == 'done')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Done</span>
            @elseif($task->status == 'canceled')
                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">Canceled</span>
            @endif
        </div>

        <div class="pt-2">
            <span class="block text-sm font-bold text-gray-700 mb-1">Description</span>
            <div class="p-4 bg-gray-50 rounded-md border text-gray-700 whitespace-pre-wrap min-h-[150px]">
                {{ $task->description ?: 'Empty' }}
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <a href="{{ route('tasks.edit', ['project_id' => $task->project_id, 'taskId' => $task->id]) }}" wire:navigate class="bg-yellow-600 text-white px-4 py-2 rounded-md font-medium hover:bg-yellow-700 shadow text-sm transition">
                Edit Task
            </a>
        </div>
    </div>
</div>
