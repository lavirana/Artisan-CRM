<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
            
            <div class="flex items-end justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2 tracking-wider">Search Network</label>
                    <input 
                        wire:model.live="search" 
                        type="text" 
                        placeholder="Search by name, email, or company..." 
                        class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                    />
                </div>
                
                <div>
                    <button 
                        wire:click="create" 
                        class="inline-flex items-center justify-center h-[42px] px-5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg shadow transition whitespace-nowrap"
                    style="color:grey">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="stroke-width: 2.5; stroke-linecap: round;">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add Contact
                    </button>
                </div>
            </div>

            @if (session()->has('message'))
                <div class="p-4 mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold">Name</th>
                            <th class="px-6 py-4 font-bold">Email Address</th>
                            <th class="px-6 py-4 font-bold">Company</th>
                            <th class="px-6 py-4 font-bold">Job Title</th>
                            <th class="px-6 py-4 font-bold text-right">Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($contacts as $contact)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">{{ $contact->fullName }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $contact->email }}</td>
                                <td class="px-6 py-4"><span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">{{ $contact->company->name ?? 'None Assigned' }}</span></td>
                                <td class="px-6 py-4 text-gray-500">{{ $contact->job_title ?? '-' }}</td>
                                <td class="px-6 py-4 text-right text-xs font-semibold space-x-3">
                                    <button wire:click="edit({{ $contact->id }})" class="text-indigo-600 hover:text-indigo-900 transition">Edit</button>
                                    <button wire:click="delete({{ $contact->id }})" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900 transition">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-gray-50/30">
                                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    No records found in your pipeline matching that search query.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $contacts->links() }}
            </div>

            @if($isModalOpen)
                <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm">
                    <div class="relative w-full max-w-md p-6 bg-white rounded-xl shadow-xl border border-gray-100">
                        <div class="flex items-center justify-between mb-5 pb-2 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900">{{ $contactId ? 'Update Contact Profile' : 'Create New Lead Contact' }}</h3>
                            <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        
                        <form wire:submit.prevent="save" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">First Name</label>
                                    <input type="text" wire:model="first_name" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    @error('first_name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Last Name</label>
                                    <input type="text" wire:model="last_name" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    @error('last_name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Email Address</label>
                                <input type="email" wire:model="email" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Phone Number</label>
                                <input type="text" wire:model="phone" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @error('phone') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Job Designation</label>
                                <input type="text" wire:model="job_title" placeholder="e.g. Sales Manager" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @error('job_title') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Associated Corporate Entity</label>
                                <select wire:model="company_id" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">Select Corporate Account</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Discard</button>
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">Commit Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>