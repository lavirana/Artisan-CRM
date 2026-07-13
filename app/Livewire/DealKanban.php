<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Deal;
use App\Models\Contact;
use App\Models\Company;

class DealKanban extends Component
{
    // Define the rigid steps of our sales pipeline
    public array $stages = ['lead', 'qualification', 'proposal', 'won', 'lost'];
    
    // Modal state properties
    public $isModalOpen = false;
    public $title, $value, $stage = 'lead', $contact_id, $company_id;
    public $dealId;

    public function render()
    {
        // Gather all deals grouped by their current pipeline stage column
        $deals = Deal::with(['contact', 'company'])->latest()->get();

        return view('livewire.deal-kanban', [
            'deals' => $deals,
            'contacts' => Contact::orderBy('first_name')->get(),
            'companies' => Company::orderBy('name')->get()
        ])->layout('layouts.app');
    }

    // Explicit method executed when dragging or clicking a card to shift its column stage
    public function updateDealStage($dealId, $newStage)
    {
        if (in_array($newStage, $this->stages)) {
            $deal = Deal::findOrFail($dealId);
            
            $updateData = ['stage' => $newStage];
            if (in_array($newStage, ['won', 'lost'])) {
                $updateData['closed_at'] = now();
            } else {
                $updateData['closed_at'] = null;
            }

            $deal->update($updateData);
            session()->flash('message', "Deal moved to " . ucfirst($newStage));
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $validatedData = $this->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'stage' => 'required|in:' . implode(',', $this->stages),
            'contact_id' => 'nullable|exists:contacts,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        if (!$this->dealId) {
            $validatedData['user_id'] = auth()->id();
        }

        Deal::updateOrCreate(['id' => $this->dealId], $validatedData);

        session()->flash('message', $this->dealId ? 'Deal modified successfully.' : 'New pipeline deal committed.');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Deal::findOrFail($id)->delete();
        session()->flash('message', 'Deal dropped from pipeline execution records.');
    }

    private function resetForm()
    {
        $this->dealId = null;
        $this->title = '';
        $this->value = 0;
        $this->stage = 'lead';
        $this->contact_id = null;
        $this->company_id = null;
    }
}