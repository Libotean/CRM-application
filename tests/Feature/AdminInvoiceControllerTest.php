<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminInvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_displays_invoices()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->get(route('admin.invoices.index'));

        $response->assertStatus(200);
        $response->assertSee((string)$invoice->amount);
    }

    public function test_store_creates_invoice()
    {
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'amount' => 500,
            'status' => 'unpaid',
        ];

        $response = $this->post(route('admin.invoices.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', $data);
    }

    public function test_update_edits_invoice()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->put(route('admin.invoices.update', $invoice), [
            'client_id' => $invoice->client_id,
            'amount' => 999,
            'status' => 'paid',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'amount' => 999,
            'status' => 'paid',
        ]);
    }

    public function test_destroy_deletes_invoice()
    {
        $invoice = Invoice::factory()->create();

        $response = $this->delete(route('admin.invoices.destroy', $invoice));

        $response->assertRedirect();
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }
}
