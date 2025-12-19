<?php

namespace Tests\Feature;

use App\Filament\Resources\Tickets\Schemas\TicketForm;
use App\Models\Endpoint;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Component as LivewireComponent;
use Tests\TestCase;

class TestSchemaHostTickets extends LivewireComponent implements HasSchemas
{
    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function getOldSchemaState(string $statePath): mixed
    {
        return null;
    }

    public function getSchemaComponent(string $key, bool $withHidden = false, array $skipComponentsChildContainersWhileSearching = []): \Filament\Schemas\Components\Component|\Filament\Actions\Action|\Filament\Actions\ActionGroup|null
    {
        return null;
    }

    public function getSchema(string $name): ?Schema
    {
        return null;
    }

    public function currentlyValidatingSchema(?Schema $schema): void {}

    public function getDefaultTestingSchemaName(): ?string
    {
        return null;
    }
}

class TicketFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_status_and_priority_are_required_and_have_expected_options(): void
    {
        $schema = TicketForm::configure(Schema::make(new TestSchemaHostTickets));

        $status = $schema->getComponent(function ($component) {
            return ($component instanceof Select) && ($component->getName() === 'status');
        }, false, true);
        $this->assertInstanceOf(Select::class, $status);
        $this->assertTrue($status->isRequired());
        $this->assertArrayHasKey('open', $status->getOptions());
        $this->assertArrayHasKey('in_progress', $status->getOptions());
        $this->assertArrayHasKey('resolved', $status->getOptions());

        $priority = $schema->getComponent(function ($component) {
            return ($component instanceof Select) && ($component->getName() === 'priority');
        }, false, true);
        $this->assertInstanceOf(Select::class, $priority);
        $this->assertTrue($priority->isRequired());
        $this->assertArrayHasKey('low', $priority->getOptions());
        $this->assertArrayHasKey('medium', $priority->getOptions());
        $this->assertArrayHasKey('high', $priority->getOptions());
    }

    public function test_endpoint_options_are_populated_from_database(): void
    {
        Endpoint::create(['hostname' => 'host-a', 'api_token' => 'tok-a', 'status' => 'offline']);
        Endpoint::create(['hostname' => 'host-b', 'api_token' => 'tok-b', 'status' => 'offline']);

        $schema = TicketForm::configure(Schema::make(new TestSchemaHostTickets));
        $endpointSelect = $schema->getComponent(function ($component) {
            return ($component instanceof Select) && ($component->getName() === 'endpoint_id');
        }, false, true);

        $this->assertInstanceOf(Select::class, $endpointSelect);
        $options = $endpointSelect->getOptions();
        $this->assertNotEmpty($options);
        $this->assertContains('host-a', array_values($options));
        $this->assertContains('host-b', array_values($options));
    }

    public function test_user_id_is_required_and_defaults_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $schema = TicketForm::configure(Schema::make(new TestSchemaHostTickets));
        $userSelect = $schema->getComponent(function ($component) {
            return ($component instanceof Select) && ($component->getName() === 'user_id');
        }, false, true);

        $this->assertInstanceOf(Select::class, $userSelect);
        $this->assertTrue($userSelect->isRequired());
    }
}
