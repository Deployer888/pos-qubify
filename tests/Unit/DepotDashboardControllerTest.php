<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Depot;
use App\Models\DepotSale;
use App\Http\Controllers\Admin\DepotDashboardController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use ReflectionClass;

class DepotDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $superAdmin;
    protected $depotManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controller = new DepotDashboardController();
        
        // Create roles
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Depot Manager']);
        
        // Create users
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');
        
        $this->depotManager = User::factory()->create();
        $this->depotManager->assignRole('Depot Manager');
    }

    /** @test */
    public function validate_decimal_value_handles_valid_numbers()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateDecimalValue');
        $method->setAccessible(true);

        // Test valid decimal
        $result = $method->invoke($this->controller, 123.45);
        $this->assertEquals(123.45, $result);

        // Test integer
        $result = $method->invoke($this->controller, 100);
        $this->assertEquals(100.00, $result);

        // Test string number
        $result = $method->invoke($this->controller, '50.25');
        $this->assertEquals(50.25, $result);
    }

    /** @test */
    public function validate_decimal_value_handles_invalid_input()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateDecimalValue');
        $method->setAccessible(true);

        // Test null
        $result = $method->invoke($this->controller, null);
        $this->assertEquals(0.00, $result);

        // Test non-numeric string
        $result = $method->invoke($this->controller, 'invalid');
        $this->assertEquals(0.00, $result);

        // Test negative number
        $result = $method->invoke($this->controller, -100);
        $this->assertEquals(0.00, $result);

        // Test extremely large number
        $result = $method->invoke($this->controller, 9999999999.99);
        $this->assertEquals(0.00, $result);
    }

    /** @test */
    public function format_currency_returns_proper_format()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('formatCurrency');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller, 1234.56);
        $this->assertEquals('₹ 1,234.56', $result);

        $result = $method->invoke($this->controller, 0);
        $this->assertEquals('₹ 0.00', $result);

        $result = $method->invoke($this->controller, 'invalid');
        $this->assertEquals('₹ 0.00', $result);
    }

    /** @test */
    public function validate_user_access_allows_super_admin()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateUserAccess');
        $method->setAccessible(true);

        // Should not throw exception for Super Admin
        $this->expectNotToPerformAssertions();
        $method->invoke($this->controller, $this->superAdmin);
    }

    /** @test */
    public function validate_user_access_allows_depot_manager()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateUserAccess');
        $method->setAccessible(true);

        // Should not throw exception for Depot Manager
        $this->expectNotToPerformAssertions();
        $method->invoke($this->controller, $this->depotManager);
    }

    /** @test */
    public function validate_user_access_rejects_unauthorized_user()
    {
        $unauthorizedUser = User::factory()->create();
        // No role assigned
        
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateUserAccess');
        $method->setAccessible(true);

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $method->invoke($this->controller, $unauthorizedUser);
    }

    /** @test */
    public function validate_user_access_rejects_null_user()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateUserAccess');
        $method->setAccessible(true);

        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        $method->invoke($this->controller, null);
    }

    /** @test */
    public function get_dashboard_data_by_role_returns_super_admin_data()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getDashboardDataByRole');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller, $this->superAdmin);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_depots', $result);
        $this->assertArrayHasKey('active_depots', $result);
        $this->assertArrayHasKey('month_revenue', $result);
    }

    /** @test */
    public function get_dashboard_data_by_role_returns_depot_manager_data()
    {
        // Create depot for manager
        Depot::factory()->create(['user_id' => $this->depotManager->id]);
        
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getDashboardDataByRole');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller, $this->depotManager);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('assigned_depot', $result);
    }

    /** @test */
    public function validate_dashboard_data_sanitizes_invalid_data()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateDashboardData');
        $method->setAccessible(true);

        $invalidData = [
            'total_depots' => 'invalid',
            'month_revenue' => null,
            'valid_field' => 'valid_value'
        ];

        $result = $method->invoke($this->controller, $invalidData);
        
        $this->assertEquals(0, $result['total_depots']);
        $this->assertEquals(0, $result['month_revenue']);
        $this->assertEquals('valid_value', $result['valid_field']);
    }

    /** @test */
    public function validate_dashboard_data_handles_non_array_input()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateDashboardData');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller, 'not_an_array');
        
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /** @test */
    public function cache_dashboard_data_stores_data_correctly()
    {
        Cache::flush(); // Clear cache
        
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('cacheDashboardData');
        $method->setAccessible(true);

        $testData = ['test' => 'value'];
        
        $method->invoke($this->controller, $this->superAdmin, $testData);
        
        // Check if data was cached
        $cacheKey = 'dashboard_data_' . $this->superAdmin->id;
        $cachedData = Cache::get($cacheKey);
        
        $this->assertNotNull($cachedData);
        $this->assertArrayHasKey('data', $cachedData);
        $this->assertEquals($testData, $cachedData['data']);
    }

    /** @test */
    public function revenue_calculation_precision_test()
    {
        // Create sales with precise decimal values
        $depot = Depot::factory()->create();
        
        DepotSale::factory()->create([
            'depot_id' => $depot->id,
            'total' => 10.33,
            'created_at' => now()
        ]);
        
        DepotSale::factory()->create([
            'depot_id' => $depot->id,
            'total' => 15.67,
            'created_at' => now()
        ]);

        Auth::login($this->superAdmin);
        
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getSuperAdminDashboardData');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller);
        
        // Should be exactly 26.00, not 26 lakh or any inflated value
        $this->assertEquals(26.00, $result['month_revenue']);
        $this->assertStringContains('₹ 26.00', $result['month_revenue_formatted']);
    }

    /** @test */
    public function depot_manager_data_isolation_test()
    {
        // Create two depots with different managers
        $depot1 = Depot::factory()->create(['user_id' => $this->depotManager->id]);
        
        $otherManager = User::factory()->create();
        $otherManager->assignRole('Depot Manager');
        $depot2 = Depot::factory()->create(['user_id' => $otherManager->id]);
        
        // Create sales for both depots
        DepotSale::factory()->create(['depot_id' => $depot1->id, 'total' => 100]);
        DepotSale::factory()->create(['depot_id' => $depot2->id, 'total' => 200]);

        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getDepotManagerDashboardData');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller, $this->depotManager);
        
        // Should only see data from assigned depot
        $this->assertEquals($depot1->id, $result['assigned_depot']->id);
        $this->assertEquals(100.00, $result['total_sales_amount']);
    }

    /** @test */
    public function error_handling_returns_fallback_data()
    {
        // Simulate error by using invalid database connection
        config(['database.default' => 'invalid']);
        
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getSuperAdminDashboardData');
        $method->setAccessible(true);

        $result = $method->invoke($this->controller);
        
        // Should return fallback data structure
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_depots', $result);
        $this->assertEquals(0, $result['total_depots']);
    }

    /** @test */
    public function performance_timeout_protection_works()
    {
        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('getDashboardDataWithTimeout');
        $method->setAccessible(true);

        // Test with very short timeout
        $result = $method->invoke($this->controller, $this->superAdmin, 1);
        
        $this->assertIsArray($result);
        // Should return some data even with timeout
    }
}