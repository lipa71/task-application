<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md mt-6 text-center">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Task Board</h1>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-200 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-200 shadow-sm animate-pulse">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 p-4 bg-indigo-50/50 border border-indigo-100 rounded-lg">
        <div>
            <label for="project_filter" class="block text-sm font-semibold text-indigo-900 mb-1">Active Project:</label>
            <select wire:model.live="selectedProjectId" id="project_filter" class="w-full border-gray-300 rounded-md p-2 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer">
                <option value="">-- Select a project --</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->tasks_count }})</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($selectedProjectId)
        <div class="mb-6 p-4 border border-gray-100 rounded-lg bg-slate-50/50 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label for="search_task" class="block text-sm font-semibold text-gray-700 mb-1">Search Tasks:</label>
                    <input type="text" wire:model.live="searchTask" id="search_task" placeholder="Type task name..." class="w-full border-gray-300 rounded-md p-2 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label for="status_filter" class="block text-sm font-semibold text-gray-700 mb-1">Filter by Status:</label>
                    <select wire:model.live="selectedStatus" id="status_filter" class="w-full border-gray-300 rounded-md p-2 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer">
                        <option value="">-- All Statuses --</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-semibold text-gray-700 mb-1">Date From:</label>
                    <div class="relative flex items-center">
                        <input type="date" wire:model.live="dateFrom" id="date_from" max="{{ $dateTo }}" onkeydown="return false" class="w-full border-gray-300 rounded-md p-2 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer pr-8">
                        @if($dateFrom)
                            <button type="button" wire:click="$set('dateFrom', '')" class="absolute right-2 text-gray-400 hover:text-red-500 font-bold text-sm cursor-pointer select-none focus:outline-none">✕</button>
                        @endif
                    </div>
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-semibold text-gray-700 mb-1">Date To:</label>
                    <div class="relative flex items-center">
                        <input type="date" wire:model.live="dateTo" id="date_to" min="{{ $dateFrom }}" onkeydown="return false" class="w-full border-gray-300 rounded-md p-2 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer pr-8">
                        @if($dateTo)
                            <button type="button" wire:click="$set('dateTo', '')" class="absolute right-2 text-gray-400 hover:text-red-500 font-bold text-sm cursor-pointer select-none focus:outline-none">✕</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-gray-200/50">
                <a href="{{ route('tasks.create', ['project_id' => $selectedProjectId]) }}" wire:navigate class="inline-block bg-blue-600 text-white px-5 py-2 rounded-md font-medium hover:bg-blue-700 shadow text-center whitespace-nowrap text-sm transition cursor-pointer">
                    + New Task
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-100 p-3 grid grid-cols-12 font-bold text-sm text-gray-600 border-b border-gray-200 items-center">
                <div class="col-span-1 text-center">Drag</div>
                <div class="col-span-1 text-center">Order</div>
                <div class="col-span-3">Task Name</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-2 text-center">
                    <button wire:click="toggleDateSort" class="flex items-center justify-center gap-1 w-full hover:text-indigo-600 transition cursor-pointer font-bold">
                        Created At
                        <span>{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                    </button>
                </div>
                <div class="col-span-3 text-right">Actions</div>
            </div>

            <ul class="divide-y divide-gray-200"
                x-init="
                    Sortable.create($el, {
                        animation: 175,
                        handle: '.drag-handle',
                        ghostClass: 'bg-indigo-50',
                        chosenClass: 'shadow-lg',
                        filter: '[data-sortable=\'false\']',
                        onEnd: function () {
                            let orderedIds = [];
                            $el.querySelectorAll('li[data-id]').forEach((item) => {
                                orderedIds.push(item.getAttribute('data-id'));
                            });
                            $wire.call('reorderTasks', orderedIds);
                        }
                    });
                ">
                @forelse($tasks as $index => $task)
                    <li data-id="{{ $task->id }}" class="p-3 grid grid-cols-12 items-center bg-white hover:bg-slate-50 transition duration-150">
                        <div class="col-span-1 text-center text-gray-400 drag-handle cursor-grab active:cursor-grabbing font-bold text-lg select-none">
                            ::
                        </div>
                        <div class="col-span-1 font-mono text-indigo-600 font-bold text-center">#{{ $index + 1 }}</div>
                        <div class="col-span-3">
                            <a href="{{ route('tasks.show', $task->id) }}" wire:navigate class="text-gray-800 font-semibold hover:text-indigo-600 hover:underline block truncate cursor-pointer">
                                {{ $task->name }}
                            </a>
                        </div>

                        <div class="col-span-2 text-center">
                            @if($task->status === 'in_progress')
                                <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">In Progress</span>
                            @elseif($task->status === 'done')
                                <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-200">Done</span>
                            @elseif($task->status === 'canceled')
                                <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-200">Canceled</span>
                            @endif
                        </div>

                        <div class="col-span-2 text-center text-xs text-gray-500 font-medium">
                            {{ $task->created_at->format('Y-m-d H:i') }}
                        </div>

                        <div class="col-span-3 flex justify-end space-x-3">
                            <a href="{{ route('tasks.edit', ['project_id' => $selectedProjectId, 'taskId' => $task->id]) }}" wire:navigate class="text-yellow-600 hover:text-yellow-800 text-sm font-semibold cursor-pointer">
                                Edit
                            </a>
                            <button wire:click="deleteTask({{ $task->id }})" wire:confirm="Are you sure you want to delete this task?" class="text-red-600 hover:text-red-800 text-sm font-semibold cursor-pointer">
                                Delete
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-gray-400 bg-gray-50/50" data-sortable="false">
                        Empty
                    </li>
                @endforelse
            </ul>
        </div>

        <div class="mt-5 pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="w-full sm:w-auto flex items-center gap-2 text-sm text-gray-600">
                <span>Display:</span>
                <select wire:model.live="perPage" id="per_page" class="border-gray-300 rounded-md p-1 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm cursor-pointer">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15 tasks</option>
                    <option value="20">20 tasks</option>
                    <option value="50">50 tasks</option>
                    <option value="100">100 tasks</option>
                </select>
            </div>
        </div>
        <div class="w-full sm:w-auto custom-pagination">
            {{ $tasks->links(data: ['scrollTo' => false]) }}
        </div>
</div>
@endif

