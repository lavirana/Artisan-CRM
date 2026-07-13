<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div>
                <h2 class="text-2xl font-bold text-gray-900">CRM Intelligence Analytics Dashboard</h2>
                <p class="text-sm text-gray-500">Real-time revenue visibility, operational volumes, and deal pipeline conversion dynamics</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-bold uppercase text-gray-400 tracking-wider">Active Pipeline Value</div>
                    <div class="text-2xl font-black text-indigo-600 mt-2">${{ number_format($totalPipelineValue, 2) }}</div>
                    <p class="text-[11px] text-gray-400 mt-1">Unclosed opportunities value</p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-bold uppercase text-gray-400 tracking-wider">Closed Won Revenue</div>
                    <div class="text-2xl font-black text-emerald-600 mt-2">${{ number_format($closedWonValue, 2) }}</div>
                    <p class="text-[11px] text-gray-400 mt-1">Booked cash contributions</p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-bold uppercase text-gray-400 tracking-wider">Conversion Win Rate</div>
                    <div class="text-2xl font-black text-gray-900 mt-2">{{ $conversionRate }}%</div>
                    <p class="text-[11px] text-gray-400 mt-1">Ratio of Won vs Lost deals</p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <div class="text-xs font-bold uppercase text-gray-400 tracking-wider">Operational Backlog</div>
                    <div class="text-2xl font-black text-amber-600 mt-2">{{ $pendingTasksCount }} Pending</div>
                    <p class="text-[11px] text-red-500 font-semibold mt-1">{{ $overdueTasksCount }} Tasks Overdue</p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-6">Pipeline Stage Distribution</h3>
                    
                    <div class="space-y-4">
                        @php 
                            $maxCount = max(array_merge($stageCounts, [1])); // Avoid dividing by zero
                        @endphp

                        @foreach($stageCounts as $stage => $count)
                            @php 
                                $percentage = ($count / $maxCount) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-semibold mb-1 text-gray-700">
                                    <span class="uppercase tracking-wide">{{ $stage }}</span>
                                    <span>{{ $count }} {{ Str::plural('Deal', $count) }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-7 rounded-lg overflow-hidden relative border border-slate-200/60">
                                    <div 
                                        class="h-full transition-all duration-500 rounded-r-md
                                            {{ $stage === 'won' ? 'bg-emerald-500' : '' }}
                                            {{ $stage === 'lost' ? 'bg-rose-400' : '' }}
                                            {{ !in_array($stage, ['won', 'lost']) ? 'bg-indigo-500' : '' }}"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">Database Asset Matrix</h3>
                    
                    <div class="divide-y divide-gray-100 text-sm">
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Total Managed Contacts</span>
                            <span class="font-extrabold text-gray-900 bg-slate-100 px-2.5 py-0.5 rounded-full text-xs">{{ $totalContacts }}</span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Gross Tracked Opportunities</span>
                            <span class="font-extrabold text-gray-900 bg-slate-100 px-2.5 py-0.5 rounded-full text-xs">{{ $totalDealsCount }}</span>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <span class="text-gray-500 font-medium">Successful Conversions</span>
                            <span class="font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $wonDealsCount }} Closed</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400 italic">Data aggregates update live based on account entries.</p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>