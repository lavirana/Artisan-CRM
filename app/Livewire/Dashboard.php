<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\Task;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Calculate Pipeline Financial Matrices
        $totalPipelineValue = Deal::whereNotIn('stage', ['won', 'lost'])->sum('value');
        $closedWonValue = Deal::where('stage', 'won')->sum('value');
        
        // 2. Compute Volume Counts
        $totalDealsCount = Deal::count();
        $wonDealsCount = Deal::where('stage', 'won')->count();
        $lostDealsCount = Deal::where('stage', 'lost')->count();
        
        // 3. Prevent Division by Zero and compute conversion rate
        $closedDealsCount = $wonDealsCount + $lostDealsCount;
        $conversionRate = $closedDealsCount > 0 
            ? round(($wonDealsCount / $closedDealsCount) * 100, 1) 
            : 0;

        // 4. Gather Counts for Pipeline Stage Bar Chart distribution
        $stageCounts = [];
        foreach (['lead', 'qualification', 'proposal', 'won', 'lost'] as $stage) {
            $stageCounts[$stage] = Deal::where('stage', $stage)->count();
        }

        // 5. Gather Operational Task Summaries
        $pendingTasksCount = Task::where('status', 'pending')->count();
        $overdueTasksCount = Task::where('status', 'pending')
            ->where('due_date', '<', now()->startOfDay())
            ->count();

        return view('livewire.dashboard', [
            'totalPipelineValue' => $totalPipelineValue,
            'closedWonValue' => $closedWonValue,
            'conversionRate' => $conversionRate,
            'wonDealsCount' => $wonDealsCount,
            'totalDealsCount' => $totalDealsCount,
            'stageCounts' => $stageCounts,
            'pendingTasksCount' => $pendingTasksCount,
            'overdueTasksCount' => $overdueTasksCount,
            'totalContacts' => Contact::count(),
        ])->layout('layouts.app');
    }
}