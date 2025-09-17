<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Depot;
use App\Models\DepotStock;
use App\Models\DepotCustomer;
use App\Models\State;
use Spatie\Permission\Models\Role;

class PanchkulaDepotSeeder extends Seeder
{
    /**
     * Authentic Panchkula localities and areas
     */
    private $panchkulaAreas = [
        'Sector 1, Panchkula',
        'Sector 2, Panchkula',
        'Sector 3, Panchkula',
        'Sector 4, Panchkula',
        'Sector 5, Panchkula',
        'Sector 6, Panchkula',
        'Sector 7, Panchkula',
        'Sector 8, Panchkula',
        'Sector 9, Panchkula',
        'Sector 10, Panchkula',
        'Sector 11, Panchkula',
        'Sector 12, Panchkula',
        'Sector 14, Panchkula',
        'Sector 15, Panchkula',
        'Sector 16, Panchkula',
        'Sector 17, Panchkula',
        'Sector 18, Panchkula',
        'Sector 19, Panchkula',
        'Sector 20, Panchkula',
        'Sector 21, Panchkula',
        'Mansa Devi Complex, Panchkula',
        'Industrial Area, Panchkula',
        'Chandimandir, Panchkula',
        'Barwala, Panchkula',
        'Raipur Rani, Panchkula',
        'Kalka, Panchkula'
    ];

    /**
     * Authentic Indian male names
     */
    private $indianMaleNames = [
        'Rajesh', 'Suresh', 'Ramesh', 'Mahesh', 'Dinesh', 'Naresh', 'Mukesh', 'Rakesh',
        'Amit', 'Sumit', 'Rohit', 'Mohit', 'Ajit', 'Lalit', 'Ankit', 'Arpit',
        'Vikash', 'Prakash', 'Akash', 'Aakash', 'Subhash', 'Vikas', 'Pankaj', 'Neeraj',
        'Sanjay', 'Vijay', 'Ajay', 'Manoj', 'Anil', 'Sunil', 'Kapil', 'Rahul',
        'Deepak', 'Ashok', 'Vinod', 'Pramod', 'Jagdish', 'Harish', 'Girish', 'Manish',
        'Ravi', 'Shiv', 'Dev', 'Arjun', 'Karan', 'Varun', 'Tarun', 'Arun'
    ];

    /**
     * Authentic Indian female names
     */
    private $indianFemaleNames = [
        'Sunita', 'Anita', 'Geeta', 'Seeta', 'Meera', 'Neera', 'Veena', 'Reena',
        'Priya', 'Kavya', 'Divya', 'Shreya', 'Pooja', 'Sooja', 'Monika', 'Deepika',
        'Asha', 'Usha', 'Nisha', 'Risha', 'Sushma', 'Pushpa', 'Kamla', 'Sharmila',
        'Rekha', 'Lekha', 'Radha', 'Sudha', 'Vidya', 'Bindya', 'Sangeeta', 'Vineeta',
        'Mamta', 'Samta', 'Shanti', 'Kranti', 'Bharti', 'Shakti', 'Mukti', 'Preeti',
        'Kiran', 'Suman', 'Raman', 'Chaman', 'Pawan', 'Jyoti', 'Moti', 'Soni'
    ];

    /**
     * Common Indian surnames
     */
    private $indianSurnames = [
        'Sharma', 'Gupta', 'Singh', 'Kumar', 'Verma', 'Agarwal', 'Jain', 'Bansal',
        'Mittal', 'Goel', 'Arora', 'Malhotra', 'Chopra', 'Kapoor', 'Bhatia', 'Sethi',
        'Aggarwal', 'Jindal', 'Singhal', 'Goyal', 'Saxena', 'Srivastava', 'Tiwari', 'Pandey',
        'Mishra', 'Shukla', 'Dubey', 'Tripathi', 'Chaturvedi', 'Dwivedi', 'Pathak', 'Joshi',
        'Bhardwaj', 'Chauhan', 'Rajput', 'Thakur', 'Yadav', 'Reddy', 'Nair', 'Menon'
    ];

    /**
     * PDS (Public Distribution System) products with specifications
     */
    private $pdsProducts = [
        [
            'name' => 'Rice (Common)',
            'unit' => 'Kg',
            'min_stock' => 50,
            'max_stock' => 200,
            'market_price' => 25.00,
            'subsidized_price' => 3.00
        ],
        [
            'name' => 'Rice (Basmati)',
            'unit' => 'Kg',
            'min_stock' => 20,
            'max_stock' => 100,
            'market_price' => 80.00,
            'subsidized_price' => 5.00
        ],
        [
            'name' => 'Wheat Flour',
            'unit' => 'Kg',
            'min_stock' => 100,
            'max_stock' => 300,
            'market_price' => 30.00,
            'subsidized_price' => 2.00
        ],
        [
            'name' => 'Sugar',
            'unit' => 'Kg',
            'min_stock' => 50,
            'max_stock' => 150,
            'market_price' => 45.00,
            'subsidized_price' => 13.50
        ],
        [
            'name' => 'Kerosene',
            'unit' => 'Ltr',
            'min_stock' => 200,
            'max_stock' => 500,
            'market_price' => 60.00,
            'subsidized_price' => 14.96
        ],
        [
            'name' => 'Mustard Oil',
            'unit' => 'Ltr',
            'min_stock' => 50,
            'max_stock' => 200,
            'market_price' => 150.00,
            'subsidized_price' => 25.00
        ]
    ];

    /**
     * Ration card categories with distribution percentages
     */
    private $rationCardCategories = [
        'APL' => 40, // Above Poverty Line - 40%
        'BPL' => 45, // Below Poverty Line - 45%
        'AAY' => 15  // Antyodaya Anna Yojana - 15%
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting Panchkula Depot Data Seeding...');
        
        // Ask user what to do with existing depot stock
        $this->handleExistingDepotStock();
        
        // Create depot managers
        $managers = $this->createDepotManagers();
        
        // Create depots
        $depots = $this->createDepots($managers);
        
        // Create stock inventory for each depot
        $this->createDepotStock($depots);
        
        // Create customers and families for each depot
        $this->createDepotCustomers($depots);
        
        $this->command->info('Panchkula Depot Data Seeding completed successfully!');
    }

    /**
     * Create 10 depot managers with authentic Indian details
     *
     * @return void
     */
    private function createDepotManagers()
    {
        $this->command->info('Creating depot managers...');
        
        // Ensure Depot Manager role exists
        $depotManagerRole = Role::firstOrCreate([
            'name' => 'Depot Manager',
            'guard_name' => 'web'
        ]);
        
        // Check if we already have Panchkula depot managers
        $existingManagers = User::role('Depot Manager')
            ->where('email', 'like', '%@panchkula.gov.in')
            ->count();
            
        if ($existingManagers >= 10) {
            $this->command->info('Panchkula depot managers already exist. Skipping creation.');
            return User::role('Depot Manager')
                ->where('email', 'like', '%@panchkula.gov.in')
                ->limit(10)
                ->get();
        }
        
        $managers = [];
        $managersToCreate = 10 - $existingManagers;
        
        for ($i = 1; $i <= $managersToCreate; $i++) {
            // Generate authentic Indian name using deterministic approach
            $name = $this->generateIndianName($i);
            
            // Generate unique email
            $email = $this->generateUniqueEmail($name, $existingManagers + $i);
            
            // Generate Indian mobile number
            $mobile = $this->generateIndianMobile($i);
            
            // Create or find user to prevent duplicates
            $manager = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'phone' => $mobile,
                    'password' => Hash::make('password123'), // Default password
                    'status' => User::STATUS_ACTIVE,
                ]
            );
            
            // Assign depot manager role
            if (!$manager->hasRole('Depot Manager')) {
                $manager->assignRole($depotManagerRole);
            }
            
            $managers[] = $manager;
            
            $this->command->info("Created depot manager: {$name} ({$email})");
        }
        
        $this->command->info('Successfully created ' . count($managers) . ' new depot managers.');
        
        return $managers;
    }

    /**
     * Generate authentic Indian name (male or female)
     *
     * @param int $index
     * @return string
     */
    private function generateIndianName($index = null)
    {
        if ($index !== null) {
            // Use deterministic approach for consistent results
            $isMale = $index % 2 === 1; // Alternate between male and female
            
            if ($isMale) {
                $firstName = $this->indianMaleNames[($index - 1) % count($this->indianMaleNames)];
            } else {
                $firstName = $this->indianFemaleNames[($index - 1) % count($this->indianFemaleNames)];
            }
            
            $surname = $this->indianSurnames[($index - 1) % count($this->indianSurnames)];
        } else {
            // Randomly choose male or female name
            $isMale = rand(0, 1);
            
            if ($isMale) {
                $firstName = $this->indianMaleNames[array_rand($this->indianMaleNames)];
            } else {
                $firstName = $this->indianFemaleNames[array_rand($this->indianFemaleNames)];
            }
            
            $surname = $this->indianSurnames[array_rand($this->indianSurnames)];
        }
        
        return $firstName . ' ' . $surname;
    }

    /**
     * Generate unique email address for manager
     *
     * @param string $name
     * @param int $index
     * @return string
     */
    private function generateUniqueEmail($name, $index)
    {
        // Convert name to email format
        $emailName = strtolower(str_replace(' ', '.', $name));
        $emailName = preg_replace('/[^a-z0-9.]/', '', $emailName);
        
        // Add depot prefix and index for uniqueness
        $email = "depot.manager.{$index}.{$emailName}@panchkula.gov.in";
        
        // Ensure uniqueness by checking if email already exists
        $counter = 1;
        $originalEmail = $email;
        while (User::where('email', $email)->exists()) {
            $email = str_replace('@panchkula.gov.in', ".{$counter}@panchkula.gov.in", $originalEmail);
            $counter++;
        }
        
        return $email;
    }

    /**
     * Generate Indian mobile number in +91 format
     *
     * @param int $index
     * @return string
     */
    private function generateIndianMobile($index = null)
    {
        if ($index !== null) {
            // Use deterministic approach for consistent results
            $firstDigit = [6, 7, 8, 9][($index - 1) % 4];
            
            // Generate remaining 9 digits based on index
            $remainingDigits = str_pad($index, 9, '0', STR_PAD_LEFT);
            $remainingDigits = substr($remainingDigits, -9); // Take last 9 digits
        } else {
            // Indian mobile numbers start with 6, 7, 8, or 9
            $firstDigit = [6, 7, 8, 9][array_rand([6, 7, 8, 9])];
            
            // Generate remaining 9 digits
            $remainingDigits = '';
            for ($i = 0; $i < 9; $i++) {
                $remainingDigits .= rand(0, 9);
            }
        }
        
        return '+91' . $firstDigit . $remainingDigits;
    }

    /**
     * Create 10 depots in Panchkula with authentic addresses
     *
     * @param array $managers
     * @return void
     */
    private function createDepots($managers)
    {
        $this->command->info('Creating depots in Panchkula...');
        
        // Get Haryana state ID
        $haryanaState = State::where('name', 'Haryana')->first();
        if (!$haryanaState) {
            $this->command->error('Haryana state not found in database. Please run states seeder first.');
            return;
        }
        
        // Check if we already have Panchkula depots
        $existingDepots = Depot::where('city', 'Panchkula')->count();
        
        if ($existingDepots >= 10) {
            $this->command->info('Panchkula depots already exist. Skipping creation.');
            return Depot::where('city', 'Panchkula')->get();
        }
        
        // Fair Price Shop types for variety
        $depotTypes = [
            'Fair Price Shop',
            'Ration Depot',
            'PDS Center',
            'Government Ration Shop'
        ];
        
        $depotsToCreate = 10 - $existingDepots;
        $createdDepots = [];
        
        for ($i = 1; $i <= $depotsToCreate; $i++) {
            // Get manager for this depot (ensure we have enough managers)
            $managerIndex = ($i - 1) % count($managers);
            $manager = $managers[$managerIndex];
            
            // Check if this manager already has a depot in Panchkula
            $existingDepot = Depot::where('user_id', $manager->id)
                ->where('city', 'Panchkula')
                ->first();
                
            if ($existingDepot) {
                $this->command->info("Manager {$manager->name} already has a depot in Panchkula. Skipping.");
                continue;
            }
            
            // Get authentic Panchkula address
            $address = $this->getAuthenticPanchkulaAddress($i);
            
            // Select depot type
            $depotType = $depotTypes[($i - 1) % count($depotTypes)];
            
            // Create depot
            $depot = Depot::firstOrCreate(
                [
                    'address' => $address,
                    'city' => 'Panchkula'
                ],
                [
                    'user_id' => $manager->id,
                    'depot_type' => $depotType,
                    'state' => $haryanaState->id,
                    'status' => 'active'
                ]
            );
            
            $createdDepots[] = $depot;
            
            $this->command->info("Created depot: {$depotType} at {$address} (Manager: {$manager->name})");
        }
        
        $this->command->info('Successfully created ' . count($createdDepots) . ' new depots in Panchkula.');
        
        // Return all Panchkula depots (existing + newly created)
        return Depot::where('city', 'Panchkula')->get();
    }

    /**
     * Get authentic Panchkula address for depot
     *
     * @param int $index
     * @return string
     */
    private function getAuthenticPanchkulaAddress($index)
    {
        // Use deterministic approach to ensure consistent addresses
        $areaIndex = ($index - 1) % count($this->panchkulaAreas);
        $area = $this->panchkulaAreas[$areaIndex];
        
        // Generate shop/building number
        $shopNumbers = ['Shop No. 1', 'Shop No. 2', 'Shop No. 3', 'Building No. 4', 'Plot No. 5', 'House No. 6'];
        $shopNumber = $shopNumbers[($index - 1) % count($shopNumbers)];
        
        // Generate street/market names
        $streetNames = ['Main Market', 'Central Market', 'Local Market', 'Community Center', 'Shopping Complex', 'Market Area'];
        $streetName = $streetNames[($index - 1) % count($streetNames)];
        
        return "{$shopNumber}, {$streetName}, {$area}";
    }

    /**
     * Create stock inventory for PDS products in each depot
     *
     * @param \Illuminate\Database\Eloquent\Collection $depots
     * @return void
     */
    private function createDepotStock($depots)
    {
        $this->command->info('Creating stock inventory for depots...');
        
        if (!$depots || $depots->isEmpty()) {
            $this->command->error('No depots found to create stock for.');
            return;
        }
        
        $totalStockCreated = 0;
        
        foreach ($depots as $depot) {
            $this->command->info("Creating stock for depot at: {$depot->address}");
            
            // Check if this depot already has stock
            $existingStock = DepotStock::where('depot_id', $depot->id)->count();
            
            if ($existingStock > 0) {
                $this->command->info("Depot already has {$existingStock} stock items. Skipping stock creation.");
                continue;
            }
            
            // Create stock for each PDS product
            foreach ($this->pdsProducts as $productIndex => $product) {
                // Generate realistic stock quantity within the specified range
                $stockQuantity = rand($product['min_stock'], $product['max_stock']);
                
                // Generate unique barcode for this product in this depot
                $barcode = $this->generateUniqueBarcode($depot->id, $productIndex);
                
                // Create stock entry
                $stock = DepotStock::firstOrCreate(
                    [
                        'depot_id' => $depot->id,
                        'product_name' => $product['name']
                    ],
                    [
                        'measurement_unit' => $product['unit'],
                        'current_stock' => $stockQuantity,
                        'price' => $product['market_price'],
                        'customer_price' => $product['subsidized_price'],
                        'barcode' => $barcode
                    ]
                );
                
                $totalStockCreated++;
                
                $this->command->info("  - {$product['name']}: {$stockQuantity} {$product['unit']} (Market: ₹{$product['market_price']}, Subsidized: ₹{$product['subsidized_price']}) [Barcode: {$barcode}]");
            }
        }
        
        $this->command->info("Successfully created {$totalStockCreated} stock entries across " . $depots->count() . " depots.");
    }

    /**
     * Generate unique barcode for depot stock
     *
     * @param int $depotId
     * @param int $productIndex
     * @return string
     */
    private function generateUniqueBarcode($depotId, $productIndex)
    {
        // Generate barcode in format: PDS-{DEPOT_ID}-{PRODUCT_INDEX}-{RANDOM}
        // This ensures uniqueness across all depot stocks
        $randomSuffix = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $barcode = "PDS-" . str_pad($depotId, 3, '0', STR_PAD_LEFT) . "-" . str_pad($productIndex + 1, 2, '0', STR_PAD_LEFT) . "-" . $randomSuffix;
        
        // Ensure barcode is unique in database
        $counter = 1;
        $originalBarcode = $barcode;
        while (DepotStock::where('barcode', $barcode)->exists()) {
            $barcode = $originalBarcode . "-" . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $barcode;
    }

    /**
     * Handle existing depot stock - either clear all or generate missing barcodes
     *
     * @return void
     */
    private function handleExistingDepotStock()
    {
        $totalStocks = DepotStock::count();
        $stocksWithoutBarcodes = DepotStock::whereNull('barcode')->orWhere('barcode', '')->count();
        
        if ($totalStocks === 0) {
            $this->command->info('No existing depot stock found. Will create fresh stock.');
            return;
        }
        
        $this->command->info("Found {$totalStocks} existing depot stock entries.");
        if ($stocksWithoutBarcodes > 0) {
            $this->command->info("{$stocksWithoutBarcodes} entries are missing barcodes.");
        }
        
        $choice = $this->command->choice(
            'What would you like to do with existing depot stock?',
            [
                'keep' => 'Keep existing stock and generate missing barcodes',
                'clear' => 'Clear all existing stock and create fresh',
                'skip' => 'Skip stock operations entirely'
            ],
            'keep'
        );
        
        switch ($choice) {
            case 'clear':
                $this->clearAllDepotStock();
                break;
            case 'keep':
                $this->generateMissingBarcodes();
                break;
            case 'skip':
                $this->command->info('Skipping all stock operations.');
                break;
        }
    }

    /**
     * Clear all existing depot stock entries
     *
     * @return void
     */
    private function clearAllDepotStock()
    {
        $this->command->info('Clearing all existing depot stock entries...');
        
        $deletedCount = DepotStock::count();
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            DepotStock::truncate();
            $this->command->info("Successfully deleted {$deletedCount} depot stock entries.");
        } catch (\Exception $e) {
            // If truncate fails, use delete instead
            $this->command->info('Truncate failed, using delete method...');
            DepotStock::query()->delete();
            $this->command->info("Successfully deleted {$deletedCount} depot stock entries using delete.");
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Generate barcodes for existing stock entries that are missing them
     *
     * @return void
     */
    private function generateMissingBarcodes()
    {
        $this->command->info('Generating barcodes for existing stock entries...');
        
        $stocksWithoutBarcodes = DepotStock::whereNull('barcode')->orWhere('barcode', '')->get();
        
        if ($stocksWithoutBarcodes->isEmpty()) {
            $this->command->info('All existing stock entries already have barcodes.');
            return;
        }
        
        $updatedCount = 0;
        
        foreach ($stocksWithoutBarcodes as $stock) {
            // Find the product index for barcode generation
            $productIndex = 0;
            foreach ($this->pdsProducts as $index => $product) {
                if ($product['name'] === $stock->product_name) {
                    $productIndex = $index;
                    break;
                }
            }
            
            // Generate unique barcode
            $barcode = $this->generateUniqueBarcode($stock->depot_id, $productIndex);
            
            // Update the stock entry
            $stock->update(['barcode' => $barcode]);
            
            $updatedCount++;
            
            $this->command->info("  - Updated {$stock->product_name} (Depot ID: {$stock->depot_id}) with barcode: {$barcode}");
        }
        
        $this->command->info("Successfully generated barcodes for {$updatedCount} stock entries.");
    }

    /**
     * Create customers and families for each depot (15-25 customers per depot)
     *
     * @param \Illuminate\Database\Eloquent\Collection $depots
     * @return void
     */
    private function createDepotCustomers($depots)
    {
        $this->command->info('Creating customers and families for depots...');
        
        if (!$depots || $depots->isEmpty()) {
            $this->command->error('No depots found to create customers for.');
            return;
        }
        
        $totalCustomersCreated = 0;
        $totalFamiliesCreated = 0;
        
        foreach ($depots as $depot) {
            $this->command->info("Creating customers for depot at: {$depot->address}");
            
            // Check if this depot already has customers
            $existingCustomers = DepotCustomer::where('depot_id', $depot->id)->count();
            
            if ($existingCustomers >= 15) {
                $this->command->info("Depot already has {$existingCustomers} customers. Skipping customer creation.");
                continue;
            }
            
            // Generate 15-25 customers per depot
            $customersToCreate = rand(15, 25) - $existingCustomers;
            
            // Create families (3-5 customers per family on average)
            $averageFamilySize = 4;
            $familiesToCreate = max(1, intval($customersToCreate / $averageFamilySize));
            
            $depotCustomersCreated = 0;
            $depotFamiliesCreated = 0;
            
            for ($familyIndex = 1; $familyIndex <= $familiesToCreate; $familyIndex++) {
                // Generate unique family ID
                $familyId = $this->generateUniqueFamilyId($depot->id, $familyIndex);
                
                // Determine family size (2-6 members)
                $familySize = rand(2, 6);
                
                // Adjust family size if we're approaching the customer limit
                $remainingCustomers = $customersToCreate - $depotCustomersCreated;
                $remainingFamilies = $familiesToCreate - $familyIndex + 1;
                
                if ($remainingFamilies > 1) {
                    $maxFamilySize = min($familySize, $remainingCustomers - ($remainingFamilies - 1));
                    $familySize = max(1, $maxFamilySize);
                } else {
                    // Last family gets all remaining customers
                    $familySize = $remainingCustomers;
                }
                
                if ($familySize <= 0) break;
                
                $this->command->info("  Creating family {$familyIndex} with {$familySize} members (Family ID: {$familyId})");
                
                // Create family members
                for ($memberIndex = 1; $memberIndex <= $familySize; $memberIndex++) {
                    $isHead = ($memberIndex === 1); // First member is family head
                    
                    $customer = $this->createCustomer($depot, $familyId, $isHead, $memberIndex);
                    
                    if ($customer) {
                        $depotCustomersCreated++;
                        $totalCustomersCreated++;
                        
                        $headStatus = $isHead ? ' (Family Head)' : '';
                        $this->command->info("    - Created: {$customer->name}, Age: {$customer->age}, Card: {$customer->card_range}{$headStatus}");
                    }
                }
                
                $depotFamiliesCreated++;
                $totalFamiliesCreated++;
            }
            
            $this->command->info("Created {$depotCustomersCreated} customers in {$depotFamiliesCreated} families for this depot.");
        }
        
        $this->command->info("Successfully created {$totalCustomersCreated} customers in {$totalFamiliesCreated} families across all depots.");
    }

    /**
     * Create a single customer with authentic Indian details
     *
     * @param \App\Models\Depot $depot
     * @param string $familyId
     * @param bool $isHead
     * @param int $memberIndex
     * @return \App\Models\DepotCustomer|null
     */
    private function createCustomer($depot, $familyId, $isHead, $memberIndex)
    {
        // Generate authentic Indian name
        $name = $this->generateIndianName();
        
        // Generate unique Aadhaar number
        $aadhaarNo = $this->generateUniqueAadhaarNumber();
        
        // Generate unique ration card number (all family members share the same card number)
        $rationCardNo = $this->generateUniqueRationCardNumber($familyId);
        
        // Assign card range based on distribution percentages
        $cardRange = $this->assignCardRange();
        
        // Generate realistic age (18-80 years, family head tends to be older)
        $age = $this->generateRealisticAge($isHead, $memberIndex);
        
        // Generate Indian mobile number
        $mobile = $this->generateIndianMobile();
        
        // Use depot address as customer address (same locality)
        $address = $this->generateCustomerAddress($depot->address);
        
        try {
            $customer = DepotCustomer::firstOrCreate(
                [
                    'adhaar_no' => $aadhaarNo,
                    'depot_id' => $depot->id
                ],
                [
                    'family_id' => $familyId,
                    'ration_card_no' => $rationCardNo,
                    'card_range' => $cardRange,
                    'name' => $name,
                    'mobile' => $mobile,
                    'age' => $age,
                    'is_family_head' => $isHead,
                    'address' => $address,
                    'status' => 'active'
                ]
            );
            
            return $customer;
        } catch (\Exception $e) {
            $this->command->error("Failed to create customer {$name}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate unique family ID for a depot
     *
     * @param int $depotId
     * @param int $familyIndex
     * @return string
     */
    private function generateUniqueFamilyId($depotId, $familyIndex)
    {
        // Format: PNKL-{DEPOT_ID}-FAM-{FAMILY_INDEX}
        $familyId = "PNKL-" . str_pad($depotId, 3, '0', STR_PAD_LEFT) . "-FAM-" . str_pad($familyIndex, 3, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        $counter = 1;
        $originalFamilyId = $familyId;
        while (DepotCustomer::where('family_id', $familyId)->exists()) {
            $familyId = $originalFamilyId . "-" . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $familyId;
    }

    /**
     * Generate unique Aadhaar number (12 digits)
     *
     * @return string
     */
    private function generateUniqueAadhaarNumber()
    {
        do {
            // Generate 12-digit Aadhaar number (format: XXXX XXXX XXXX)
            $aadhaar = '';
            for ($i = 0; $i < 12; $i++) {
                $aadhaar .= rand(0, 9);
            }
            
            // Format with spaces for readability
            $formattedAadhaar = substr($aadhaar, 0, 4) . ' ' . substr($aadhaar, 4, 4) . ' ' . substr($aadhaar, 8, 4);
            
            // Ensure it doesn't start with 0 or 1 (as per Aadhaar rules)
            if (in_array($aadhaar[0], ['0', '1'])) {
                continue;
            }
            
        } while (DepotCustomer::where('adhaar_no', $formattedAadhaar)->exists());
        
        return $formattedAadhaar;
    }

    /**
     * Generate unique ration card number following Indian format
     *
     * @param string $familyId
     * @return string
     */
    private function generateUniqueRationCardNumber($familyId)
    {
        // Indian ration card format: HR{DISTRICT_CODE}{BLOCK_CODE}{SERIAL_NUMBER}
        // For Panchkula: HR06 (Haryana state code 06) + PNK (Panchkula) + serial
        
        // Extract family index from family ID for uniqueness
        preg_match('/FAM-(\d+)/', $familyId, $matches);
        $familyIndex = isset($matches[1]) ? $matches[1] : rand(1000, 9999);
        
        do {
            $rationCard = "HR06PNK" . str_pad($familyIndex, 6, '0', STR_PAD_LEFT);
            $familyIndex++; // Increment for next attempt if needed
        } while (DepotCustomer::where('ration_card_no', $rationCard)->exists());
        
        return $rationCard;
    }

    /**
     * Assign card range based on distribution percentages
     *
     * @return string
     */
    private function assignCardRange()
    {
        $random = rand(1, 100);
        
        if ($random <= $this->rationCardCategories['AAY']) {
            return 'AAY'; // 15%
        } elseif ($random <= $this->rationCardCategories['AAY'] + $this->rationCardCategories['APL']) {
            return 'APL'; // 40%
        } else {
            return 'BPL'; // 45%
        }
    }

    /**
     * Generate realistic age distribution (18-80 years)
     *
     * @param bool $isHead
     * @param int $memberIndex
     * @return int
     */
    private function generateRealisticAge($isHead, $memberIndex)
    {
        if ($isHead) {
            // Family heads are typically older (25-65 years)
            return rand(25, 65);
        } else {
            // Family members have varied ages
            if ($memberIndex === 2) {
                // Second member (spouse) - similar age to head
                return rand(22, 60);
            } else {
                // Children or elderly parents
                $ageGroup = rand(1, 3);
                switch ($ageGroup) {
                    case 1: // Children/Young adults
                        return rand(5, 25);
                    case 2: // Adults
                        return rand(26, 50);
                    case 3: // Elderly
                        return rand(51, 80);
                    default:
                        return rand(18, 60);
                }
            }
        }
    }

    /**
     * Generate customer address based on depot location
     *
     * @param string $depotAddress
     * @return string
     */
    private function generateCustomerAddress($depotAddress)
    {
        // Extract area from depot address
        $areaParts = explode(',', $depotAddress);
        $area = end($areaParts); // Get the last part (area name)
        
        // Generate house/flat numbers
        $houseNumbers = [
            'House No. ' . rand(1, 999),
            'Flat No. ' . rand(1, 50) . ', Block ' . chr(rand(65, 90)),
            'Plot No. ' . rand(1, 200),
            'Building No. ' . rand(1, 100),
            'Apartment ' . rand(1, 30) . ', Tower ' . rand(1, 5)
        ];
        
        $houseNumber = $houseNumbers[array_rand($houseNumbers)];
        
        // Generate street names
        $streetNames = [
            'Main Road',
            'Market Road',
            'Gandhi Road',
            'Nehru Street',
            'Park Avenue',
            'Central Street',
            'Station Road',
            'School Road',
            'Hospital Road',
            'Temple Street'
        ];
        
        $streetName = $streetNames[array_rand($streetNames)];
        
        return "{$houseNumber}, {$streetName}, {$area}";
    }
}
