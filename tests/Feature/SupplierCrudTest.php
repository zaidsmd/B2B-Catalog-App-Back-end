<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Supplier\Enums\SupplierType;
use Modules\Supplier\Models\Supplier;

uses(RefreshDatabase::class);

it('can list suppliers', function () {
    Supplier::create([
        'full_name' => 'Supplier One',
        'type' => SupplierType::cases()[0]->value,
        'email' => 'supplier1@example.com',
    ]);

    $response = $this->getJson('/api/admin/v1/suppliers');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);
});

it('can create a supplier', function () {
    $data = [
        'full_name' => 'New Supplier',
        'type' => SupplierType::cases()[0]->value,
        'email' => 'new@example.com',
        'phone' => '123456789',
        'address' => '123 Street',
    ];

    $response = $this->postJson('/api/admin/v1/suppliers', $data);

    $response->assertStatus(201)
        ->assertJsonFragment(['full_name' => 'New Supplier']);

    $this->assertDatabaseHas('suppliers', ['full_name' => 'New Supplier']);
});

it('can show a supplier', function () {
    $supplier = Supplier::create([
        'full_name' => 'Supplier To Show',
        'type' => SupplierType::cases()[0]->value,
    ]);

    $response = $this->getJson("/api/admin/v1/suppliers/{$supplier->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['full_name' => 'Supplier To Show']);
});

it('can update a supplier', function () {
    $supplier = Supplier::create([
        'full_name' => 'Old Name',
        'type' => SupplierType::cases()[0]->value,
    ]);

    $data = [
        'full_name' => 'Updated Name',
    ];

    $response = $this->putJson("/api/admin/v1/suppliers/{$supplier->id}", $data);

    $response->assertStatus(200)
        ->assertJsonFragment(['full_name' => 'Updated Name']);

    $this->assertDatabaseHas('suppliers', [
        'id' => $supplier->id,
        'full_name' => 'Updated Name',
    ]);
});

it('can delete a supplier', function () {
    $supplier = Supplier::create([
        'full_name' => 'To Delete',
        'type' => SupplierType::cases()[0]->value,
    ]);

    $response = $this->deleteJson("/api/admin/v1/suppliers/{$supplier->id}");

    $response->assertStatus(204);

    $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
});
