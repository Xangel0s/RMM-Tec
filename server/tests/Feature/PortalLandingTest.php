<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalLandingTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_landing_page_loads_correctly(): void
    {
        $response = $this->get('/portal');

        $response->assertStatus(200);
        $response->assertSee('stickets');
        $response->assertSee('Support ticketing that');
        $response->assertSee('Client Portal');
    }

    public function test_support_page_loads_correctly(): void
    {
        $response = $this->get('/portal/support');

        $response->assertStatus(200);
        $response->assertSee('Centro de Ayuda'); // Assuming this text is in minimal.blade.php
    }
}
