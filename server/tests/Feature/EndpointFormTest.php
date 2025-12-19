<?php

namespace Tests\Feature;

use App\Filament\Resources\Endpoints\Schemas\EndpointForm;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component as LivewireComponent;
use Tests\TestCase;

class TestSchemaHost extends LivewireComponent implements HasSchemas
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

class EndpointFormTest extends TestCase
{
    public function test_hostname_is_required_and_has_max_length(): void
    {
        $schema = EndpointForm::configure(Schema::make(new TestSchemaHost));
        $hostname = $schema->getComponent(function ($component) {
            return ($component instanceof TextInput) && ($component->getName() === 'hostname');
        }, false, true);

        $this->assertInstanceOf(TextInput::class, $hostname);
        $this->assertTrue($hostname->isRequired());
        $this->assertContains('max:255', $hostname->getValidationRules());
    }

    public function test_connection_fields_have_correct_validations(): void
    {
        $schema = EndpointForm::configure(Schema::make(new TestSchemaHost));

        $publicIp = $schema->getComponent(function ($component) {
            return ($component instanceof TextInput) && ($component->getName() === 'public_ip');
        }, false, true);
        $this->assertInstanceOf(TextInput::class, $publicIp);
        $this->assertContains('ipv4', $publicIp->getValidationRules());

        $localIp = $schema->getComponent(function ($component) {
            return ($component instanceof TextInput) && ($component->getName() === 'local_ip');
        }, false, true);
        $this->assertInstanceOf(TextInput::class, $localIp);
        $this->assertContains('ipv4', $localIp->getValidationRules());

        $rustdeskId = $schema->getComponent(function ($component) {
            return ($component instanceof TextInput) && ($component->getName() === 'rustdesk_id');
        }, false, true);
        $this->assertInstanceOf(TextInput::class, $rustdeskId);
        $this->assertContains('numeric', $rustdeskId->getValidationRules());
    }

    public function test_api_token_is_disabled_and_not_dehydrated(): void
    {
        $schema = EndpointForm::configure(Schema::make(new TestSchemaHost));

        $apiToken = $schema->getComponent(function ($component) {
            return ($component instanceof TextInput) && ($component->getName() === 'api_token');
        }, false, true);

        $this->assertInstanceOf(TextInput::class, $apiToken);
        $this->assertTrue($apiToken->isDisabled());
        $this->assertFalse($apiToken->isDehydrated());
    }
}
