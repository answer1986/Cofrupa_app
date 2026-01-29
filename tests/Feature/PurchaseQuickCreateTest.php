<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseQuickCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_quick_create_purchase_with_new_fields()
    {
        $this->withoutExceptionHandling();

        // Create a user and supplier
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'name' => 'Test Supplier', 
            'rut' => '12345678-9',
            'phone' => '123456789',
        ]);

        // Simulated form data
        $data = [
            'supplier_id' => $supplier->id,
            'buyer' => 'Cofrupa',
            'purchase_type' => 'fruta',
            'purchase_date' => now()->format('Y-m-d'),
            'weight_purchased' => 1000,
            'calibre' => '80-90',
            'units_per_pound' => 12,
            'unit_price' => 500, // New field
            'notes' => 'Test Note', // New field
        ];

        // Authenticate and submit the form
        $response = $this->actingAs($user)
                         ->post(route('purchases.quick-store'), $data);

        // Assert redirection to edit page
        $purchase = Purchase::first();
        $response->assertRedirect(route('purchases.edit', $purchase));

        // Assert database has correct values
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'supplier_id' => $supplier->id,
            'unit_price' => 500,
            'total_amount' => 500000, // 1000 * 500
            'amount_owed' => 500000,
            'notes' => 'Test Note',
        ]);

        // Assert supplier debt updated
        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'total_debt' => 500000,
        ]);
    }
}
