<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 pb-6 border-b border-gray-100 items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Activity Logs & Assets</h2>
                    <p class="text-xs text-gray-500">Document interactions, append contract documents, and view histories</p>
                </div>
                <div class="flex gap-3 justify-end">
                    <select wire:model.live="contactFilter" class="px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500">
                        <option value="">All Contacts</option>
                        @foreach($contacts as $c) <option value="{{ $c->id }}">{{ $c->fullName }}</option> @endforeach
                    </select>
                    <select wire:model.live="dealFilter" class="px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500">
                        <option value="">All Deals</option>
                        @foreach($deals as $d) <option value="{{ $d->id }}">{{ $d->title }}</option> @endforeach
                    </select>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="p-4 mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg shadow-sm">{{ session('message') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="p-4 mb-6 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Log Interaction</h3>
                    
                    <form wire:submit.prevent="saveNote" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Interaction Notes</label>
                            <textarea wire:model="content" rows="4" placeholder="Summarize call parameters, pricing decisions, next action alignment..." class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-indigo-500"></textarea>
                            @error('content') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Append Document Asset (Optional)</label>
                            <input type="file" wire:model="attachment" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                            <div wire:loading wire:target="attachment" class="text-xs text-indigo-600 font-medium mt-1">Uploading file payload stream...</div>
                            @error('attachment') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Relate Contact</label>
                                <select wire:model="contact_id" class="w-full p-2 border border-gray-300 rounded-md text-xs">
                                    <option value="">None</option>
                                    @foreach($contacts as $cnt) <option value="{{ $cnt->id }}">{{ $cnt->fullName }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase text-gray-500 mb-1">Relate Deal</label>
                                <select wire:model="deal_id" class="w-full p-2 border border-gray-300 rounded-md text-xs">
                                    <option value="">None</option>
                                    @foreach($deals as $dl) <option value="{{ $dl->id }}">{{ $dl->title }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                            Commit Timeline Entry
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-2 space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-2">History Log</h3>
                    
                    @forelse($notes as $note)
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative group transition hover:border-gray-300">
                            <div class="flex items-start justify-between mb-2">
                                <div class="text-xs text-gray-400 font-medium">
                                    Logged by <span class="text-gray-700 font-bold">{{ $note->user->name }}</span> 
                                    &bull; {{ $note->created_at->diffForHumans() }}
                                </div>
                                <button wire:click="deleteNote({{ $note->id }})" onclick="confirm('Drop item from record?') || event.stopImmediatePropagation()" class="text-gray-300 hover:text-red-500 text-sm transition">&times;</button>
                            </div>

                            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line">{{ $note->content }}</p>

                            @if($note->file_path)
                                <div class="mt-4 p-2.5 bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between text-xs">
                                    <div class="flex items-center text-slate-600 truncate mr-4">
                                        <svg class="w-4 h-4 mr-2 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span class="truncate font-medium">{{ $note->file_name }}</span>
                                    </div>
                                    <button wire:click="downloadFile({{ $note->id }})" class="text-indigo-600 font-bold hover:underline flex-shrink-0">
                                        Download
                                    </button>
                                </div>
                            @endif

                            @if($note->contact || $note->deal)
                                <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-2">
                                    @if($note->contact)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded-full border border-blue-100">Contact: {{ $note->contact->fullName }}</span>
                                    @endif
                                    @if($note->deal)
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 rounded-full border border-purple-100">Opportunity: {{ $note->deal->title }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl py-12 text-center text-sm font-medium text-gray-400">
                            No interaction histories found matching current selection parameters.
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $notes->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>