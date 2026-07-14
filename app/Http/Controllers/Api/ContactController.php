<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Securely fetch contacts belonging only to the authenticated user/team
        $contacts = Contact::with('company')->where('user_id', auth()->id())->paginate(10);
        return ContactResource::collection($contacts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'company_id' => 'nullable|exists:companies,id'
        ]);

        $validated['user_id'] = auth()->id();
        $contact = Contact::create($validated);

        return new ContactResource($contact);
    }

    public function show(Contact $contact)
    {
        // Step 4: Enforce precise permissions boundaries
        if ($contact->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return new ContactResource($contact->load('company'));
    }

    // ... implement update() and destroy() matching this same pattern
}