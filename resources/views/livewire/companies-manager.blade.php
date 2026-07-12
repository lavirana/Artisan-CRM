<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                
                <!-- Top Action Bar -->
                <div class="flex items-end justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                    <div class="flex-1">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2 tracking-wider">Search Companies</label>
                        <input 
                            wire:model.live="search" 
                            type="text" 
                            placeholder="Search by corporate name or industry..." 
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                        />
                    </div>
                    
                    <div>
                        <button 
                            wire:click="create" 
                            class="inline-flex items-center justify-center h-[42px] px-5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg shadow transition whitespace-nowrap"
                     >
                            Add Company
                        </button>
                    </div>
                </div>

                <!-- Feedback Notifications -->
                @if (session()->has('message'))
                    <div class="p-4 mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Data Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold">Company Name</th>
                                <th class="px-6 py-4 font-bold">Website</th>
                                <th class="px-6 py-4 font-bold">Industry</th>
                                <th class="px-6 py-4 font-bold">Phone</th>
                                <th class="px-6 py-4 font-bold text-right">Management</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($companies as $company)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">{{ $company->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $company->website ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $company->industry ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $company->phone ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold space-x-3">
                                        <button wire:click="edit({{ $company->id }})" class="text-indigo-600 hover:underline">Edit</button>
                                        <button wire:click="delete({{ $company->id }})" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" class="text-red-600 hover:underline">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-gray-50/30">
                                        No corporate accounts found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $companies->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Overlay handles safely inside the true master wrapper element -->
    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="relative w-full max-w-md p-6 bg-white rounded-xl shadow-xl">
                <div class="flex items-center justify-between mb-5 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">{{ $companyId ? 'Update Corporate Profile' : 'Add New Corporate Account' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                </div>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Company Name</label>
                        <input type="text" wire:model="name" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Website URL</label>
                        <input type="text" wire:model="website" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                        @error('website') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Industry Verticals</label>
                        <input type="text" wire:model="industry" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Corporate Telephone</label>
                        <input type="text" wire:model="phone" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 tracking-wider">Headquarters Address</label>
                        <textarea wire:model="address" rows="2" class="w-full p-2 border border-gray-300 rounded mt-1 text-sm"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Discard</button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Commit Company</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>