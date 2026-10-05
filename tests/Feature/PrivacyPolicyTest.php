<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    /**
     * Test that the privacy policy page is publicly accessible.
     */
    public function test_privacy_policy_page_is_accessible(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('Política de Privacidade');
        $response->assertSee('DuoFin');
    }

    /**
     * Test that the Portuguese alias for privacy policy page is also accessible.
     */
    public function test_politica_de_privacidade_alias_is_accessible(): void
    {
        $response = $this->get('/politica-de-privacidade');

        $response->assertStatus(200);
        $response->assertSee('Política de Privacidade');
    }
}
