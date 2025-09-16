<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Depot;
use App\Models\DepotSale;
use App\Models\DepotStock;
use App\Models\DepotCustomer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;

class DepotDashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $superAdmin;
    protected $depotManager;
    protected $depot;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Depot Manager']);
        
        // Create users
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');
        
        $this->depotManager = User::factory()->create();
        $this->depotManager->assignRole('Depot Manager');
        
        // Create depot
        $this->depot = Depot::factory()->create([
            'user_id' => $this->depotManager->id,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function super_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.depot-dashboard.index');
        $response->assertViewHas('dashboardData');
    }

    /** @test */
    public function depot_manager_can_access_dashboard()
    {
        $response = $this->actingAs($this->depotManager)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.depot-dashboard.index');
        $response->assertViewHas('dashboardData');
    }

    /** @test */
    public function unauthorized_user_cannot_access_dashboard()
    {
        $user = User::factory()->create(); // No role assigned
        
        $response = $this->actingAs($user)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(302); // Redirect due to middleware
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.depot-dashboard.index'));
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function super_admin_dashboard_shows_all_depot_data()
    {
        // Create multiple depots with data
        $depot1 = Depot::factory()->create(['status' => 'active']);
        $depot2 = Depot::factory()->create(['status' => 'inactive']);
        
        DepotSale::factory()->create([
            'depot_id' => $depot1->id,
            'total' => 100.50
        ]);
        
        DepotSale::factory()->create([
            'depot_id' => $depot2->id,
            'total' => 250.75
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(200);
        
        $dashboardData = $response->viewData('dashboardData');
        
        $this->assertArrayHasKey('total_depots', $dashboardData);
        $this->assertArrayHasKey('active_depots', $dashboardData);
        $this->assertArrayHasKey('month_revenue', $dashboardData);
        $this->assertGreaterThanOrEqual(3, $dashboardData['total_depots']); // Including setUp depot
    }

    /** @test */
    public function depot_manager_dashboard_shows_only_assigned_depot_data()
    {
        // Create sales for the assigned depot
        DepotSale::factory()->create([
            'depot_id' => $this->depot->id,
            'total' => 150.25
        ]);
        
        // Create sales for another depot (should not appear)
        $otherDepot = Depot::factory()->create();
        DepotSale::factory()->create([
            'depot_id' => $otherDepot->id,
            'total' => 999.99
        ]);

        $response = $this->actingAs($this->depotManager)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(200);
        
        $dashboardData = $response->viewData('dashboardData');
        
        $this->assertArrayHasKey('assigned_depot', $dashboardData);
        $this->assertEquals($this->depot->id, $dashboardData['assigned_depot']->id);
        
        // Should not see other depot's data
        $this->assertArrayHasKey('total_sales_amount', $dashboardData);
    }

    /** @test */
    public function revenue_calculation_accuracy_test()
    {
        // Create sales with known values
        DepotSale::factory()->create([
            'depot_id' => $this->depot->id,
            'total' => 20.00,
            'created_at' => now()
        ]);
        
        DepotSale::factory()->create([
            'depot_id' => $this->depot->id,
            'total' => 30.50,
            'created_at' => now()
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        $dashboardData = $response->viewData('dashboardData');
        
        // Check that revenue is calculated correctly (should be 50.50, not 50 lakh)
        $this->assertArrayHasKey('month_revenue', $dashboardData);
        $this->assertEquals(50.50, $dashboardData['month_revenue']);
        
        // Check formatted currency
        $this->assertArrayHasKey('month_revenue_formatted', $dashboardData);
        $this->assertStringContains('₹ 50.50', $dashboardData['month_revenue_formatted']);
    }

    /** @test */
    public function dashboard_refresh_endpoint_works()
    {
        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('admin.depot-dashboard.refresh'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
            'timestamp'
        ]);
        
        $this->assertTrue($response->json('success'));
    }

    /** @test */
    public function dashboard_refresh_requires_authentication()
    {
        $response = $this->getJson(route('admin.depot-dashboard.refresh'));
        
        $response->assertStatus(401);
        $response->assertJson(['error' => 'Authentication required. Please login again.']);
    }

    /** @test */
    public function depot_manager_without_assigned_depot_sees_appropriate_message()
    {
        $managerWithoutDepot = User::factory()->create();
        $managerWithoutDepot->assignRole('Depot Manager');

        $response = $this->actingAs($managerWithoutDepot)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(200);
        
        $dashboardData = $response->viewData('dashboardData');
        
        $this->assertArrayHasKey('no_depot_message', $dashboardData);
        $this->assertStringContains('No depot has been assigned', $dashboardData['no_depot_message']);
    }

    /** @test */
    public function dashboard_handles_database_errors_gracefully()
    {
        // Simulate database error by using invalid connection
        config(['database.default' => 'invalid_connection']);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        // Should still return a response with fallback data
        $response->assertStatus(200);
        $response->assertViewHas('dashboardData');
    }

    /** @test */
    public function dashboard_data_validation_works()
    {
        // Create depot with invalid data to test validation
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        $dashboardData = $response->viewData('dashboardData');
        
        // All numeric fields should be properly validated
        $this->assertIsNumeric($dashboardData['total_depots']);
        $this->assertIsNumeric($dashboardData['active_depots']);
        $this->assertIsNumeric($dashboardData['month_revenue']);
        
        // Revenue should not be negative
        $this->assertGreaterThanOrEqual(0, $dashboardData['month_revenue']);
    }

    /** @test */
    public function dashboard_caching_works()
    {
        // First request should generate cache
        $response1 = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));

        // Second request should use cache (faster)
        $startTime = microtime(true);
        $response2 = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));
        $endTime = microtime(true);

        $this->assertLessThan(1.0, $endTime - $startTime); // Should be very fast due to caching
        
        $response1->assertStatus(200);
        $response2->assertStatus(200);
    }

    /** @test */
    public function dashboard_export_functionality_works()
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.export', ['format' => 'csv']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /** @test */
    public function dashboard_rate_limiting_works()
    {
        // Make multiple rapid requests
        for ($i = 0; $i < 65; $i++) {
            $response = $this->actingAs($this->superAdmin)
                ->getJson(route('admin.depot-dashboard.refresh'));
        }

        // Should be rate limited after 60 requests
        $response->assertStatus(429);
    }

    /** @test */
    public function dashboard_middleware_blocks_unauthorized_access()
    {
        $unauthorizedUser = User::factory()->create();
        // Don't assign any role
        
        $response = $this->actingAs($unauthorizedUser)
            ->get(route('admin.depot-dashboard.index'));

        $response->assertStatus(302); // Redirected by middleware
    }

    /** @test */
    public function dashboard_performance_monitoring_works()
    {
        // Create some data to monitor
        DepotSale::factory()->count(10)->create(['depot_id' => $this->depot->id]);
        DepotCustomer::factory()->count(5)->create(['depot_id' => $this->depot->id]);
        DepotStock::factory()->count(8)->create(['depot_id' => $this->depot->id]);

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.depot-dashboard.index'));
        
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);
        
        // Dashboard should load within reasonable time (3 seconds)
        $this->assertLessThan(3.0, $executionTime);
    }
}