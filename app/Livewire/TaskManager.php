<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\Contact;
use App\Models\Deal;

class TaskManager extends Component
{
    use WithPagination;
    public $search = '';
    public $sort = 'desc';
    public $statusFilter = 'pending'; // pending, completed, all

    // Form fields properties
    public $taskId;
    public $title, $description, $priority = 'medium', $status = 'pending', $due_date, $contact_id, $deal_id;

    public $isModalOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $query = Task::with(['contact', 'deal', 'user'])->latest();

        // Filter by user search input
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // Filter by completion status state
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(10);

        return view('livewire.task-manager', [
            'tasks' => $tasks,
            'contacts' => Contact::orderBy('first_name')->get(),
            'deals' => Deal::orderBy('title')->get(),
            //'users' => User::orderBy('name')->get(),
        ])->layout('layouts.app');
    }

    public function toggleComplete($id)
    {
        $task = Task::findOrFail($id);
        $task->status = $task->status === 'completed' ? 'pending' : 'completed';
        $task->save();
        
        session()->flash('message', 'Task status updated.');
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->priority = $task->priority;
        $this->status = $task->status;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : null;
        $this->contact_id = $task->contact_id;
        $this->deal_id = $task->deal_id;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $validatedData = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,completed',
            'due_date' => 'nullable|date',
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
        ]);

        if (!$this->taskId) {
            $validatedData['user_id'] = auth()->id();
        }

        Task::updateOrCreate(['id' => $this->taskId], $validatedData);

        session()->flash('message', $this->taskId ? 'Task updated successfully.' : 'Task assigned successfully.');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();
        session()->flash('message', 'Task dropped completely.');
    }

    private function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->priority = 'medium';
        $this->status = 'pending';
        $this->due_date = date('Y-m-d');
        $this->contact_id = null;
        $this->deal_id = null;
    }
}
