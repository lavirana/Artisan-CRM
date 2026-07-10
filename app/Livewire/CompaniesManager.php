<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Company;

class CompaniesManager extends Component
{
    public function render()
    {
        return view('livewire.companies-manager');
    }
}
