<?php

use App\Filament\Resources\Systems\Pages\CreateSystem;
use App\Filament\Resources\Systems\Pages\EditSystem;
use App\Filament\Resources\Systems\Pages\ListSystems;
use App\Filament\Resources\Systems\SystemResource;
use App\Models\System;
use App\Models\User;
use Filament\Actions\DeleteAction;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel(Filament::getPanel('app'));
});

// ── List Page ────────────────────────────────────────────────

it('can render the list page', function () {
    $this->get(SystemResource::getUrl('index'))->assertSuccessful();
});

it('can list systems', function () {
    $systems = System::factory()->count(5)->create();

    livewire(ListSystems::class)
        ->assertCanSeeTableRecords($systems);
});

// ── Create Page ──────────────────────────────────────────────

it('can render the create page', function () {
    $this->get(SystemResource::getUrl('create'))->assertSuccessful();
});

it('can create a system', function () {
    $newData = System::factory()->make();

    livewire(CreateSystem::class)
        ->fillForm($newData->toArray())
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(System::class, [
        'title' => $newData->title,
    ]);
});

it('validates required fields on create', function () {
    livewire(CreateSystem::class)
        ->fillForm([
            'title' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

// ── Edit Page ────────────────────────────────────────────────

it('can render the edit page', function () {
    $system = System::factory()->create();

    $this->get(SystemResource::getUrl('edit', ['record' => $system]))
        ->assertSuccessful();
});

it('can retrieve data on the edit page', function () {
    $system = System::factory()->create();

    livewire(EditSystem::class, ['record' => $system->getRouteKey()])
        ->assertSchemaStateSet([
            'title' => $system->title,
        ]);
});

it('can update a system', function () {
    $system = System::factory()->create();
    $newData = System::factory()->make();

    livewire(EditSystem::class, ['record' => $system->getRouteKey()])
        ->fillForm($newData->toArray())
        ->call('save')
        ->assertHasNoFormErrors();

    expect($system->refresh()->title)->toBe($newData->title);
});

it('validates required fields on edit', function () {
    $system = System::factory()->create();

    livewire(EditSystem::class, ['record' => $system->getRouteKey()])
        ->fillForm([
            'title' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'required']);
});

// ── Delete ───────────────────────────────────────────────────

it('can delete a system from the edit page', function () {
    $system = System::factory()->create();

    livewire(EditSystem::class, ['record' => $system->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($system);
});
