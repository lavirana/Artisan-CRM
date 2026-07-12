<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class CompaniesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $companyId;
    public $name, $website, $industry, $phone, $address;
    public $isModalOpen = false;


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $companies = Company::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('industry', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.companies-manager', ['companies' => $companies])->layout('layouts.app');;
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        $this->companyId = $company->id;
        $this->name = $company->name;
        $this->website = $company->website;
        $this->industry = $company->industry;
        $this->phone = $company->phone;
        $this->address = $company->address;

        $this->isModalOpen = true;
    }

    public function save(){
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'industry' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        if(!$this->companyId) {
            $validatedData['user_id'] = auth()->id();
        }

        Company::updateOrCreate(['id' => $this->companyId], $validatedData);

        session()->flash('message', $this->companyId ? 'Company updated successfully.' : 'Company created successfully.');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Company::findOrFail($id)->delete();
        session()->flash('message', 'Company deleted successfully.');
    }

    private function resetForm()
    {
        $this->companyId = null;
        $this->name = '';
        $this->website = '';
        $this->industry = '';
        $this->phone = '';
        $this->address = '';
    }

}
