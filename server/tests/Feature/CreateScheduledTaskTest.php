<?php

namespace Tests\Feature;

use App\Filament\Resources\ScheduledTasks\Pages\CreateScheduledTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class CreateScheduledTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_created_by_is_set_on_create(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $page = app(CreateScheduledTask::class);

        $method = new ReflectionMethod($page, 'mutateFormDataBeforeCreate');
        $method->setAccessible(true);
        $data = ['name' => 'Sample', 'command' => 'echo hello'];
        $result = $method->invoke($page, $data);

        $this->assertArrayHasKey('created_by', $result);
        $this->assertSame($user->id, $result['created_by']);
    }
}
