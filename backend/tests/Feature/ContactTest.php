<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test contact form submission
     */
    public function test_can_submit_contact_form(): void
    {
        $response = $this->postJson('/api/contact', [
            'organization_name' => 'Test Organization',
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'service' => 'Konsultasi SPBE',
            'message' => 'This is a test message',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Contact message saved successfully',
            ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'john@example.com',
            'organization_name' => 'Test Organization',
        ]);
    }

    /**
     * Test contact form validation
     */
    public function test_contact_form_validation_fails(): void
    {
        $response = $this->postJson('/api/contact', [
            'organization_name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['organization_name', 'full_name', 'email', 'service', 'message']);
    }
}
