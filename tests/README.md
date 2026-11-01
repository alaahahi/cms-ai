# Baseline Test Suite - Health Insurance Management System

## Overview

This test suite provides **baseline coverage** of the current system's behavior before restructuring. The goal is to ensure that refactoring doesn't break existing functionality.

## Test Structure

```
tests/
├── BaselineTestCase.php           # Base test case with common setup
├── Unit/                          # Unit tests for models
│   ├── CardModelTest.php
│   ├── UserModelTest.php
│   └── CardServiceModelTest.php
└── Feature/
    └── Baseline/                  # Feature tests for current flows
        ├── AuthTest.php
        ├── CardManagementTest.php
        ├── ProfileManagementTest.php
        ├── OrderPaymentTest.php
        ├── AppointmentTest.php
        └── ApiEndpointsTest.php
```

## Running Tests

### Prerequisites

```bash
# Install dependencies
composer install

# Ensure SQLite extension is enabled (for in-memory testing)
php -m | grep sqlite
```

### Run All Tests

```bash
# Run all tests
php artisan test

# Or using PHPUnit directly
./vendor/bin/phpunit
```

### Run Specific Test Suites

```bash
# Run only Baseline tests
php artisan test --testsuite=Baseline

# Run only Unit tests
php artisan test --testsuite=Unit

# Run a specific test file
php artisan test tests/Feature/Baseline/AuthTest.php
```

### Run with Coverage

```bash
# Generate coverage report (requires Xdebug or PCOV)
php artisan test --coverage

# With minimum coverage threshold
php artisan test --coverage --min=80
```

### Filtering Tests

```bash
# Run tests by name pattern
php artisan test --filter=test_user_can_login

# Run tests in parallel (faster)
php artisan test --parallel
```

## Test Database

Tests use **SQLite in-memory database** by default for speed. Configuration is in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

The database is:
- Created fresh for each test
- Automatically migrated
- Destroyed after test completion

## Factories

All models have factories for test data generation:

```php
// Create a card
$card = Card::factory()->create();

// Create with specific attributes
$card = Card::factory()->create([
    'price' => 850000,
]);

// Create multiple
$cards = Card::factory()->count(10)->create();

// Use states
$activeCard = Card::factory()->active()->create();
$expiredCard = Card::factory()->expired()->create();
```

Available factories:
- `CardFactory`
- `ProfileFactory`
- `CardServiceFactory`
- `CategoryFactory`
- `OrderFactory`
- `AppointmentFactory`
- `WalletFactory`
- `TransactionsFactory`

## Test Coverage

### Current Coverage

| Module | Coverage | Tests |
|--------|----------|-------|
| Authentication | ✅ | 4 tests |
| Card Management | ✅ | 7 tests |
| Profile Management | ✅ | 5 tests |
| Order & Payment | ✅ | 4 tests |
| Appointments | ✅ | 5 tests |
| API Endpoints | ✅ | 5 tests |
| Models (Unit) | ✅ | 10+ tests |

**Total: 40+ tests**

### What's Tested

✅ **Authentication flows**
- Login with valid/invalid credentials
- Logout
- Route protection

✅ **Card & Service Management**
- Card CRUD operations
- Active/expired scopes
- Card-Service relationships
- Category hierarchy

✅ **Profile (Subscriber) Management**
- Profile creation
- User/Card relationships
- Family members
- Unique constraints

✅ **Order & Payment**
- Order creation and completion
- Payment data storage
- Transaction IDs

✅ **Appointments**
- Appointment CRUD
- Relationships (User, ServiceProvider)
- Status updates (confirmed/cancelled)

✅ **API Endpoints**
- Active cards listing
- Card services by category
- Authenticated user data
- Search functionality
- Auth protection

✅ **Model Behaviors**
- Accessors & mutators
- Relationships
- Casts
- Scopes

### What's NOT Tested (Requires External Services)

⚠️ **Mocked or Skipped:**
- QiPay payment gateway (external service)
- WhatsApp API integration
- SMS verification
- File uploads (can be added with storage fake)
- Email sending

These can be tested with mocking:
```php
// Example: Mock payment service
$this->mock(QiPayService::class, function ($mock) {
    $mock->shouldReceive('makePayment')
        ->once()
        ->andReturn(['success' => true]);
});
```

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
          extensions: sqlite, pdo_sqlite
      
      - name: Install Dependencies
        run: composer install --no-interaction
      
      - name: Run Tests
        run: php artisan test --coverage --min=70
```

## Writing New Tests

### Test Naming Convention

```php
// Good
test_user_can_create_subscription()
test_provider_can_record_visit()

// Also acceptable
public function test_commission_is_calculated_correctly()
```

### Test Structure (AAA Pattern)

```php
public function test_example()
{
    // Arrange - Set up test data
    $user = User::factory()->create();
    
    // Act - Perform the action
    $response = $this->actingAs($user)->get('/dashboard');
    
    // Assert - Verify the result
    $response->assertStatus(200);
}
```

### Common Assertions

```php
// Database
$this->assertDatabaseHas('users', ['email' => 'test@example.com']);
$this->assertDatabaseMissing('users', ['email' => 'deleted@example.com']);
$this->assertDatabaseCount('users', 5);

// HTTP Response
$response->assertStatus(200);
$response->assertJson(['success' => true]);
$response->assertJsonStructure(['data' => ['id', 'name']]);

// Authentication
$this->assertAuthenticated();
$this->assertGuest();
$this->assertAuthenticatedAs($user);

// Collections
$this->assertCount(3, $collection);
$this->assertContains('value', $array);
```

## Troubleshooting

### Common Issues

**1. "Class UserType not found"**
- Solution: Ensure `UserTypeSeeder` has run
- The `BaselineTestCase` runs it automatically

**2. "SQLSTATE[HY000]: General error: 1 no such table"**
- Solution: Migrations may not be running
- Check `RefreshDatabase` trait is used
- Verify SQLite extension is installed: `php -m | grep sqlite`

**3. "Call to undefined method"**
- Solution: Model factory may be missing
- Check `database/factories/` for the model

**4. "Too many connections"**
- Solution: Tests are using MySQL instead of SQLite
- Verify `phpunit.xml` has correct DB settings

### Debug Mode

```php
// Add to any test to see queries
\DB::listen(function($query) {
    dump($query->sql, $query->bindings);
});

// Dump data
dump($user->toArray());
dd($response->content());
```

## Next Steps

After restructuring:

1. **Keep these tests passing** - They define current behavior
2. **Add new tests** for new modules (Subscription, Provider, etc.)
3. **Refactor old tests** to use new structure
4. **Maintain coverage** above 80%

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/9.x/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Database Testing](https://laravel.com/docs/9.x/database-testing)
