<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ $isEditMode ? 'Edit Task' : 'Create New Task' }}</h1>
        <a href="{{ route('tasks.index', ['project_id' => $project_id]) }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-700 font-medium cursor-pointer">&larr; Back to Board</a>
    </div>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Task Name</label>
            <input type="text" wire:model="name" placeholder="Enter task name..." class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Description / Content</label>
            <textarea wire:model="description" rows="5" placeholder="Enter task details..." class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        @if($isEditMode)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Project Assignment</label>
                <select wire:model="project_id" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <option value="">-- Select Target Project --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
                @error('project_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                <select wire:model="status" class="w-full border border-gray-300 rounded-md p-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer">
                    <option value="in_progress">In Progress</option>
                    <option value="done">Done</option>
                    <option value="canceled">Canceled</option>
                </select>
                @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        @endif

        <div class="pt-2">
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md font-medium hover:bg-indigo-700 transition shadow cursor-pointer">
                {{ $isEditMode ? 'Save Changes' : 'Create Task' }}
            </button>
        </div>
    </form>
</div>
