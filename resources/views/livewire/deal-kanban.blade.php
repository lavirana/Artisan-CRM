<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Sales Deals Pipeline</h2>
                    <p class="text-xs text-gray-500">Track and advance active opportunities through pipeline stages</p>
                </div>
                <button wire:click="create" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    + New Opportunity
                </button>
            </div>

            @if (session()->has('message'))
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-50 rounded-lg border border-green-100 shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-start">
                
                @foreach($stages as $currentStage)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 min-h-[500px] flex flex-col">
                        
                        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">{{ ucfirst($currentStage) }}</h3>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-200 text-slate-600">
                                {{ $deals->where('stage', $currentStage)->count() }}
                            </span>
                        </div>

                        <div class="space-y-3 flex-1 overflow-y-auto">
                            @forelse($deals->where('stage', $currentStage) as $deal)
                                <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:shadow transition relative group">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="text-sm font-bold text-gray-900 leading-snug">{{ $deal->title }}</h4>
                                        <button wire:click="delete({{ $deal->id }})" onclick="confirm('Drop deal?') || event.stopImmediatePropagation()" class="text-gray-300 hover:text-red-500 text-xs transition">&times;</button>
                                    </div>

                                    <p class="text-sm font-extrabold text-indigo-600 mb-2">${{ number_format($deal->value, 2) }}</p>

                                    <div class="space-y-1 mb-3">
                                        @if($deal->company)
                                            <div class="text-[11px] text-gray-500 font-medium flex items-center">
                                                <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span>
                                                {{ $deal->company->name }}
                                            </div>
                                        @endif
                                        @if($deal->contact)
                                            <div class="text-[11px] text-gray-500 font-medium flex items-center">
                                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-1.5"></span>
                                                {{ $deal->contact->fullName }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-[10px] text-slate-400 font-bold">
                                        <span>Move Stage:</span>
                                        <div class="flex space-x-1">
                                            @foreach($stages as $moveTarget)
                                                @if($moveTarget !== $currentStage)
                                                    <button 
                                                        wire:click="updateDealStage({{ $deal->id }}, '{{ $moveTarget }}')" 
                                                        title="Shift to {{ $moveTarget }}"
                                                        class="w-4 h-4 rounded bg-slate-100 hover:bg-indigo-600 hover:text-white transition uppercase flex items-center justify-center font-mono"
                                                    >
                                                        {{ substr($moveTarget, 0, 1) }}
                                                    </button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-xs font-medium text-slate-400 border border-dashed border-slate-200 rounded-lg">
                                    Empty Stage
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="relative w-full max-w-md p-6 bg-white rounded-xl shadow-xl border border-gray-100">
                <div class="flex items-center justify-between mb-5 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Add New Pipeline Deal</h3>
                    <button wire:click="$set('isModalOpen', false)" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Deal Title/Name</label>
                        <input type="text" wire:model="title" placeholder="e.g., Enterprise Software License" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Financial Contract Value ($)</label>
                        <input type="number" step="0.01" wire:model="value" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        @error('value') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Initial Pipeline Stage</label>
                        <select wire:model="stage" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                            @foreach($stages as $stg)
                                <option value="{{ $stg }}">{{ ucfirst($stg) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Primary Contact Lead</label>
                        <select wire:model="contact_id" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                            <option value="">None Linked</option>
                            @foreach($contacts as $cnt)
                                <option value="{{ $cnt->id }}">{{ $cnt->fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Associated Enterprise Account</label>
                        <select wire:model="company_id" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                            <option value="">None Linked</option>
                            @foreach($companies as $cmp)
                                <option value="{{ $cmp->id }}">{{ $cmp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$set('isModalOpen', false)" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Save Deal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>