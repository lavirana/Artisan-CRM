<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Contact;
use App\Models\Company;
use Livewire\Attributes\Layout; // 1. Import the Layout attribute

class ContactsManager extends Component
{

    use WithPagination;

    // Search and Filter parameters
    public $search = '';

    // Form fields properties
    public $contactId;
    public $first_name, $last_name, $email, $phone, $job_title, $company_id;

    // UI state flags
    public $isModalOpen = false;

    // Reset pagination when search query updates
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $contacts = Contact::with('company')
        ->where(function($query){
            $query->where('first_name', 'Like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(10);
        return view('livewire.contacts-manager',[
            'contacts' => $contacts,
            'companies' => Company::orderBy('name')->get()
        ])->layout('layouts.app');
    }

    public function openModal()
    {
        $this->isModalOpen = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }
    private function resetForm()
    {
        $this->contactId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
        $this->job_title = '';
        $this->company_id = null;
    }
    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }
    public function save(){
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $this->contactId,
            'phone' => 'nullable|string',
            'job_title' => 'nullable|string',
            'company_id' => 'nullable|exists:companies,id',
        ];

        $validatedData = $this->validate($rules);

        // Inject current authenticated user as assignee if brand new
        if (!$this->contactId) {
            $validatedData['user_id'] = auth()->id();
        }
        Contact::updateOrCreate(['id' => $this->contactId], $validatedData);

        session()->flash('message', $this->contactId ? 'Contact updated successfully.' : 'Contact created successfully.');
        
        $this->closeModal();
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $this->contactId = $contact->id;
        $this->first_name = $contact->first_name;
        $this->last_name = $contact->last_name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->job_title = $contact->job_title;
        $this->company_id = $contact->company_id;

        $this->openModal();
    }

    public function delete($id)
    {
        Contact::findOrFail($id)->delete();
        session()->flash('message', 'Contact deleted successfully.');
    }
}
