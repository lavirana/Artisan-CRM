<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                
                <div class="flex flex-col md:flex-row items-end justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2 tracking-wider">Search Tasks</label>
                        <input 
                            wire:model.live="search" 
                            type="text" 
                            placeholder="Find tasks by title..." 
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                        />
                    </div>

                    <div class="w-full md:w-auto">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2 tracking-wider">Status Filter</label>
                        <select wire:model.live="statusFilter" class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="all">All Items</option>
                        </select>
                    </div>
                    
                    <div>
                        <button 
                            wire:click="create" 
                            class="inline-flex items-center justify-center h-[45px] px-5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg shadow transition whitespace-nowrap"
                        >
                            + Add Task
                        </button>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="p-4 mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold w-12 text-center">Done</th>
                                <th class="px-6 py-4 font-bold">Task Title</th>
                                <th class="px-6 py-4 font-bold">Priority</th>
                                <th class="px-6 py-4 font-bold">Relations</th>
                                <th class="px-6 py-4 font-bold">Due Date</th>
                                <th class="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($tasks as $task)
                                <tr class="hover:bg-gray-50/70 transition {{ $task->status === 'completed' ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleComplete({{ $task->id }})" 
                                            {{ $task->status === 'completed' ? 'checked' : '' }}
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer"
                                        >
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold {{ $task->status === 'completed' ? 'line-through text-gray-400' : 'text-gray-900' }}">
                                            {{ $task->title }}
                                        </div>
                                        @if($task->description)
                                            <p class="text-xs text-gray-400 font-normal mt-0.5">{{ Str::limit($task->description, 50) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 text-[11px] font-bold uppercase rounded-full 
                                            {{ $task->priority === 'high' ? 'bg-red-50 text-red-700 border border-red-100' : '' }}
                                            {{ $task->priority === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                            {{ $task->priority === 'low' ? 'bg-slate-100 text-slate-600' : '' }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 space-y-1">
                                        @if($task->contact)
                                            <div class="text-[11px] text-gray-500"><span class="font-medium text-gray-700">Contact:</span> {{ $task->contact->fullName }}</div>
                                        @endif
                                        @if($task->deal)
                                            <div class="text-[11px] text-gray-500"><span class="font-medium text-gray-700">Deal:</span> {{ $task->deal->title }}</div>
                                        @endif
                                        @if(!$task->contact && !$task->deal)
                                            <span class="text-xs text-gray-400 italic">General task</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium {{ $task->due_date && $task->due_date->isPast() && $task->status === 'pending' ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                        {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Limit' }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold space-x-3">
                                        <button wire:click="edit({{ $task->id }})" class="text-indigo-600 hover:underline">Edit</button>
                                        <button wire:click="delete({{ $task->id }})" onclick="confirm('Delete this task permanently?') || event.stopImmediatePropagation()" class="text-red-600 hover:underline">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-gray-50/30">
                                        No upcoming tasks matching the filter configuration.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>

            </div>
        </div>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="relative w-full max-w-md p-6 bg-white rounded-xl shadow-xl border border-gray-100">
                <div class="flex items-center justify-between mb-5 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ $taskId ? 'Edit Operational Task' : 'Schedule New Task' }}</h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Action Heading</label>
                        <input type="text" wire:model="title" placeholder="e.g. Call lead back to finalize pricing" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Description / Scope Details</label>
                        <textarea wire:model="description" rows="2" placeholder="Mention important execution details..." class="w-full p-2 border border-gray-300 rounded mt-1 text-sm"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Priority Tier</label>
                            <select wire:model="priority" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Target Due Date</label>
                            <input type="date" wire:model="due_date" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Link to Lead Contact</label>
                        <select wire:model="contact_id" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                            <option value="">General (No Link)</option>
                            @foreach($contacts as $cnt)
                                <option value="{{ $cnt->id }}">{{ $cnt->fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Link to Active Pipeline Deal</label>
                        <select wire:model="deal_id" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                            <option value="">General (No Link)</option>
                            @foreach($deals as $dl)
                                <option value="{{ $dl->id }}">{{ $dl->title }} (${{ number_format($dl->value, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Discard</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Commit Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>