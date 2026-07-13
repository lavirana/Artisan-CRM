<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Contact;
use App\Models\Deal;
use App\Livewire\DealKanban;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrmCorePipelineTest extends TestCase
{
    use RefreshDatabase; // Wipes in-memory db before each run

    public function test_unauthenticated_guests_cannot_access_the_pipeline_dashboard()
    {
        $response = $this->get('/pipeline');
        $response.assertRedirect('/login');
    }

    public function test_authenticated_users_can_create_a_contact()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Contact::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'john@example.com'
        ]);
    }

    public function test_livewire_component_can_transition_deal_stages_cleanly()
    {
        $user = User::factory()->create();
        $deal = Deal::create([
            'title' => 'Enterprise SLA Deal',
            'value' => 5000.00,
            'stage' => 'lead',
            'user_id' => $user->id
        ]);

        // Act & Assert inside Livewire runtime simulator
        Livewire::actingAs($user)
            ->test(DealKanban::class)
            ->call('updateDealStage', $deal->id, 'proposal')
            ->assertStatus(200);

        $this->assertDatabaseHas('deals', [
            'id' => $deal->id,
            'stage' => 'proposal'
        ]);
    }
}