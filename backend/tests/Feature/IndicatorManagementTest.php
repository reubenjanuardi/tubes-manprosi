<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Indicator;
use App\Models\User;
use App\Models\SyncTracking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndicatorManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function it_can_fetch_public_indicators()
    {
        // Arrange
        Indicator::factory()->count(5)->create(['is_active' => true]);
        Indicator::factory()->count(2)->create(['is_active' => false]);

        // Act
        $response = $this->getJson('/api/indicators');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'indicators',
                'version',
                'last_updated'
            ]
        ]);
        
        // Should only return active indicators
        $this->assertCount(5, $response->json('data.indicators'));
    }

    /** @test */
    public function it_can_get_version_info()
    {
        // Act
        $response = $this->getJson('/api/indicators/version');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'version',
                'last_updated'
            ]
        ]);
    }

    /** @test */
    public function admin_can_list_all_indicators()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        Indicator::factory()->count(10)->create();

        // Act
        $response = $this->getJson('/api/admin/indicators');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'current_page',
                'data',
                'total'
            ]
        ]);
    }

    /** @test */
    public function admin_can_create_indicator()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        
        $data = [
            'group_name' => 'Test Group',
            'indicator_text' => 'Test Indicator',
            'type' => 'scale',
            'scale_values' => [1, 2, 3, 4, 5],
            'scale_labels' => ['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'],
            'display_order' => 1,
            'is_active' => true,
        ];

        // Act
        $response = $this->postJson('/api/admin/indicators', $data);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('indicators', [
            'group_name' => 'Test Group',
            'indicator_text' => 'Test Indicator'
        ]);
        
        // Check sync tracking was updated
        $this->assertDatabaseHas('sync_tracking', [
            'component_name' => 'indicators'
        ]);
    }

    /** @test */
    public function admin_can_update_indicator()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        $indicator = Indicator::factory()->create();
        
        $updateData = [
            'indicator_text' => 'Updated Indicator Text',
            'is_active' => false,
        ];

        // Act
        $response = $this->putJson("/api/admin/indicators/{$indicator->id}", $updateData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('indicators', [
            'id' => $indicator->id,
            'indicator_text' => 'Updated Indicator Text',
            'is_active' => false
        ]);
    }

    /** @test */
    public function admin_can_delete_indicator()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        $indicator = Indicator::factory()->create();

        // Act
        $response = $this->deleteJson("/api/admin/indicators/{$indicator->id}");

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseMissing('indicators', [
            'id' => $indicator->id
        ]);
    }

    /** @test */
    public function regular_user_cannot_access_admin_endpoints()
    {
        // Arrange
        $this->actingAs($this->regularUser, 'api');

        // Act
        $response = $this->getJson('/api/admin/indicators');

        // Assert
        $response->assertStatus(403);
        $response->assertJsonPath('success', false);
    }

    /** @test */
    public function version_increments_on_indicator_change()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        $initialVersion = Indicator::getCurrentVersion();

        // Act - Create new indicator
        $this->postJson('/api/admin/indicators', [
            'group_name' => 'Test',
            'indicator_text' => 'Test',
            'type' => 'scale',
            'scale_values' => [1, 2, 3],
            'scale_labels' => ['Low', 'Medium', 'High'],
        ]);

        // Assert
        $newVersion = Indicator::getCurrentVersion();
        $this->assertGreaterThan($initialVersion, $newVersion);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');

        // Act
        $response = $this->postJson('/api/admin/indicators', [
            'group_name' => '', // Empty required field
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['group_name', 'indicator_text']);
    }

    /** @test */
    public function it_can_filter_indicators_by_search()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        
        Indicator::factory()->create(['indicator_text' => 'Digital Literacy']);
        Indicator::factory()->create(['indicator_text' => 'Infrastructure']);

        // Act
        $response = $this->getJson('/api/admin/indicators?search=Digital');

        // Assert
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertStringContainsString('Digital', $data[0]['indicator_text']);
    }

    /** @test */
    public function it_can_filter_by_active_status()
    {
        // Arrange
        $this->actingAs($this->adminUser, 'api');
        
        Indicator::factory()->count(3)->create(['is_active' => true]);
        Indicator::factory()->count(2)->create(['is_active' => false]);

        // Act
        $response = $this->getJson('/api/admin/indicators?status=active');

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(3, $response->json('data.total'));
    }
}
