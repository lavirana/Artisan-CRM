<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // 1. Crucial import for parsing multi-part file streams
use Livewire\WithPagination;
use App\Models\Note;
use App\Models\Contact;
use App\Models\Deal;
use Illuminate\Support\Facades\Storage;

class NoteActivityCenter extends Component
{
    use WithFileUploads;
    use WithPagination;

    // Timeline filtering filters
    public $contactFilter = '';
    public $dealFilter = '';

    // Form operational bindings
    public $content = '';
    public $attachment; // Bound directly to file upload input element
    public $contact_id = '';
    public $deal_id = '';

    public function render()
    {
        $query = Note::with(['contact', 'deal', 'user']);

        // Context filtering logic layers
        if (!empty($this->contactFilter)) {
            $query->where('contact_id', $this->contactFilter);
        }
        if (!empty($this->dealFilter)) {
            $query->where('deal_id', $this->dealFilter);
        }

        $notes = $query->latest()->paginate(10);

        return view('livewire.note-activity-center', [
            'notes' => $notes,
            'contacts' => Contact::orderBy('first_name')->get(),
            'deals' => Deal::orderBy('title')->get()
        ])->layout('layouts.app');
    }

    public function saveNote()
    {
        // Enforce basic data integrity validation rules
        $this->validate([
            'content' => 'required|string|min:3',
            'attachment' => 'nullable|max:10240', // Limit uploads to 10MB maximum capacity
            'contact_id' => 'nullable|exists:contacts,id',
            'deal_id' => 'nullable|exists:deals,id',
        ]);

        $filePath = null;
        $fileName = null;

        // If a file is bundled into the request, dispatch it to private storage disk
        if ($this->attachment) {
            $filePath = $this->attachment->store('crm-attachments', 'private');
            $fileName = $this->attachment->getClientOriginalName();
        }

        Note::create([
            'content' => $this->content,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'contact_id' => $this->contact_id ?: null,
            'deal_id' => $this->deal_id ?: null,
            'user_id' => auth()->id(),
        ]);

        // Reset the form fields safely
        $this->content = '';
        $this->attachment = null;
        $this->contact_id = '';
        $this->deal_id = '';

        session()->flash('message', 'Activity note logged into timeline system.');
    }

    // Direct execution endpoint to securely retrieve stored attachments
    public function downloadFile($noteId)
    {
        $note = Note::findOrFail($noteId);

        if ($note->file_path && Storage::disk('private')->exists($note->file_path)) {
            return Storage::disk('private')->download($note->file_path, $note->file_name);
        }

        session()->flash('error', 'Requested attachment could not be located on disk layers.');
    }

    public function deleteNote($id)
    {
        $note = Note::findOrFail($id);
        
        // Remove active file footprint if existing on disk
        if ($note->file_path) {
            Storage::disk('private')->delete($note->file_path);
        }

        $note->delete();
        session()->flash('message', 'Note dropped from timeline.');
    }
}