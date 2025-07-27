<?php

namespace Tests\Feature;

use App\Models\Context;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContextsTest extends TestCase
{
    use RefreshDatabase;

    private function getContextData(array $dependencies): array
    {
        $contextData = [
            'parent_context_id' => $dependencies['rootContext']->id,
            'name' => $dependencies['name'] ?? fake()->word(),
            'order' => $dependencies['order'] ?? fake()->numberBetween(2, 999),
        ];

        if (isset($dependencies['user']?->id)) {
            $contextData['user_id'] = $dependencies['user']->id;
        }

        return $contextData;
    }

    /*
    *   context tests
    */
    public function test_show_all_user_contexts_success(): void
    {
        $dependencies1 = $this->prepareDependencies();
        $dependencies2 = $this->prepareDependencies();
        $this->createContexts(
            $dependencies1['user']->id,
            $dependencies1['rootContext']->id,
        );
        $this->createContexts(
            $dependencies2['user']->id,
            $dependencies2['rootContext']->id,
        );

        $response = $this->actingAs($dependencies1['user'])
            ->get(route('showContextData', $dependencies1['rootContext']));

        $response->assertSuccessful()->assertJsonCount(5, 'data');
    }

    public function test_show_user_context_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $context = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
            1
        );
        $response = $this->actingAs($dependencies['user'])
            ->getJson(route('context', $context[0]->id));

        $response->assertSuccessful()->assertJson([
            'data' => [
                'id' => $context[0]->id,
            ],
        ]);
    }

    public function test_show_user_context_with_incorrect_url_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $context = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
            1
        );
        $response = $this->actingAs($dependencies['user'])
            ->getJson(route('context', 'qwerty' . $context[0]->id));

        $response->assertNotFound();
    }

    public function test_show_other_user_context_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $user = $this->createUser();
        $context = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
        );
        $response = $this->actingAs($user)
            ->getJson(route('context', $context[0]->id));

        $response->assertNotFound()->assertJson([
            'message' => 'Not found',
        ]);
    }

    public function test_create_context_with_correct_data_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $contextData = $this->getContextData($dependencies);
        $response = $this->actingAs($dependencies['user'])
            ->post(route('contexts.store'), $contextData);

        $response->assertCreated();
        $this->assertDatabaseHas('contexts', [
            'name' => $contextData['name'],
            'parent_context_id' => $contextData['parent_context_id'],
        ]);
    }

    public function test_create_context_with_incorrect_data_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $contextData = $this->getContextData([...$dependencies, 'name' => '']);
        $response = $this->actingAs($dependencies['user'])
            ->post(route('contexts.store'), $contextData);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('contexts', [
            'name' => $contextData['name'],
            'parent_context_id' => $contextData['parent_context_id'],
        ]);
    }

    public function test_update_context_with_correct_data_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $contextData = $this->getContextData($dependencies);
        $context = Context::create($contextData);
        $contextData['name'] .= ' - updated';
        $response = $this->actingAs($dependencies['user'])
            ->put(route('contexts.update', $context->id), $contextData);

        $response->assertSuccessful();
        $this->assertDatabaseHas('contexts', [
            'user_id' => $dependencies['user']->id,
            'name' => $contextData['name'],
            'parent_context_id' => $contextData['parent_context_id'],
        ]);
    }

    public function test_update_context_with_incorrect_data_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $contextData = $this->getContextData($dependencies);
        $context = Context::create($contextData);
        $contextData['name'] = '';

        $response = $this->actingAs($dependencies['user'])
            ->put(route('contexts.update', $context->id), $contextData);
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('contexts', [
            'user_id' => $dependencies['user']->id,
            'name' => $contextData['name'],
            'parent_context_id' => $contextData['parent_context_id'],
        ]);
    }

    public function test_update_other_user_context_fail(): void
    {
        $dependencies1 = $this->prepareDependencies();
        $dependencies2 = $this->prepareDependencies();
        $contextData = $this->getContextData($dependencies1);
        $context = Context::create($contextData);
        $contextData['name'] .= ' - updated';

        $response = $this->actingAs($dependencies2['user'])
            ->put(route('contexts.update', $context->id), $contextData);
        $response->assertNotFound();
        $this->assertDatabaseMissing('contexts', [
            'user_id' => $dependencies1['user']->id,
            'name' => $contextData['name'],
            'parent_context_id' => $contextData['parent_context_id'],
        ]);
    }

    public function test_delete_context_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $context = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
            1
        );
        $response = $this->actingAs($dependencies['user'])
            ->delete(route('contexts.update', $context[0]->id));

        $response->assertSuccessful();
        $this->assertDatabaseMissing('contexts', [
            'user_id' => $dependencies['user']->id,
            'name' => $context[0]['name'],
            'parent_context_id' => $context[0]['parent_context_id'],
        ]);
    }

    public function test_delete_other_user_context_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $someUser = $this->createUser();
        $context = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
            1,
        );
        $response = $this->actingAs($someUser)
            ->delete(route('contexts.update', $context[0]->id));

        $response->assertNotFound()->assertJson(['message' => 'Not found']);
        $this->assertDatabaseHas('contexts', [
            'user_id' => $dependencies['user']->id,
            'name' => $context[0]['name'],
            'parent_context_id' => $context[0]['parent_context_id'],
        ]);
    }
}
