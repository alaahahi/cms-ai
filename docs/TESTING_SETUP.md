# Testing Setup Guide

## Prerequisites

Before running the baseline tests, ensure your environment has:

1. **PHP 8.0+** with required extensions:
   ```bash
   php -v  # Should show PHP 8.0 or higher
   ```

2. **Required PHP Extensions:**
   - sqlite3
   - pdo_sqlite
   - mbstring
   - xml
   - bcmath

   Check installed extensions:
   ```bash
   php -m | grep -E "(sqlite|pdo|mbstring|xml|bcmath)"
   ```

3. **Composer** (dependency manager)
   ```bash
   composer --version
   ```

## Installation Steps

### 1. Install PHP Dependencies

```bash
composer install
```

### 2. Verify Configuration

Check that `phpunit.xml` exists and has SQLite configured:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### 3. Generate Application Key (if needed)

```bash
php artisan key:generate
```

## Running Tests

### Basic Commands

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Baseline
php artisan test --testsuite=Unit

# Run specific test file
php artisan test tests/Feature/Baseline/AuthTest.php

# Run with filter
php artisan test --filter=test_user_can_login
```

### Advanced Options

```bash
# Parallel execution (faster)
php artisan test --parallel

# Stop on first failure
php artisan test --stop-on-failure

# With coverage report (requires Xdebug or PCOV)
php artisan test --coverage

# With minimum coverage threshold
php artisan test --coverage --min=80

# Verbose output
php artisan test --verbose
```

### Using PHPUnit Directly

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific suite
./vendor/bin/phpunit --testsuite=Baseline

# With coverage (HTML report)
./vendor/bin/phpunit --coverage-html coverage/

# With coverage (text report)
./vendor/bin/phpunit --coverage-text
```

## Expected Output

Successful test run should look like:

```
   PASS  Tests\Feature\Baseline\AuthTest
  ✓ user can login with valid credentials
  ✓ user cannot login with invalid credentials
  ✓ authenticated user can logout
  ✓ guest cannot access protected routes

   PASS  Tests\Feature\Baseline\CardManagementTest
  ✓ card can be created
  ✓ active scope returns only active cards
  ✓ expired scope returns only expired cards
  ✓ card service belongs to card
  ✓ card has many services
  ✓ card service can be popular
  ✓ category can have children

  Tests:  40 passed
  Time:   2.34s
```

## Troubleshooting

### Issue: "Class 'SQLite3' not found"

**Solution:**
```bash
# Ubuntu/Debian
sudo apt-get install php8.1-sqlite3

# macOS (using Homebrew)
brew install php@8.1

# Windows
# Enable extension in php.ini:
extension=sqlite3
extension=pdo_sqlite
```

### Issue: "Base table or view not found"

**Cause:** Migrations not running

**Solution:**
1. Ensure `RefreshDatabase` trait is used in test classes
2. Check database configuration in `phpunit.xml`
3. Verify migrations exist in `database/migrations/`

```bash
# List all migrations
php artisan migrate:status

# If needed, create migration
php artisan make:migration create_missing_table
```

### Issue: "Class 'UserType' not found"

**Cause:** Missing seeder execution

**Solution:**
The `BaselineTestCase` automatically runs `UserTypeSeeder`. Ensure:
1. Seeder exists: `database/seeders/UserTypeSeeder.php`
2. `user_type` table exists (check migrations)

### Issue: "Target class [CardFactory] does not exist"

**Cause:** Autoload not updated

**Solution:**
```bash
composer dump-autoload
```

### Issue: Too slow tests

**Solutions:**
1. **Use SQLite in-memory** (already configured)
2. **Run in parallel:**
   ```bash
   php artisan test --parallel
   ```
3. **Limit test scope:**
   ```bash
   php artisan test --testsuite=Unit  # Faster than Feature tests
   ```

## CI/CD Integration

### GitHub Actions

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
          extensions: sqlite3, pdo_sqlite, mbstring, xml
          coverage: xdebug
      
      - name: Copy .env
        run: |
          cp .env.example .env
          php artisan key:generate
      
      - name: Install Dependencies
        run: composer install --no-interaction --prefer-dist
      
      - name: Run Tests
        run: php artisan test --coverage --min=70
      
      - name: Upload Coverage
        uses: codecov/codecov-action@v3
        with:
          file: ./coverage.xml
```

### GitLab CI

Create `.gitlab-ci.yml`:

```yaml
image: php:8.1

stages:
  - test

test:
  stage: test
  before_script:
    - apt-get update -yqq
    - apt-get install -yqq git libzip-dev unzip
    - docker-php-ext-install zip pdo_sqlite
    - curl -sS https://getcomposer.org/installer | php
    - php composer.phar install
  script:
    - php artisan test --coverage --min=70
```

## Test Data Management

### Factories

All models have factories in `database/factories/`. Use them in tests:

```php
use App\Models\Card;

// Create one
$card = Card::factory()->create();

// Create many
$cards = Card::factory()->count(10)->create();

// With attributes
$card = Card::factory()->create([
    'price' => 850000,
]);

// Using states
$activeCard = Card::factory()->active()->create();
```

### Database Transactions

Each test runs in a transaction that's rolled back automatically:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase;  // This handles cleanup
    
    public function test_example()
    {
        // Database is clean at start
        // Any changes are rolled back after
    }
}
```

## Performance Tips

1. **Use SQLite in-memory** (default) - ~10x faster than MySQL
2. **Run tests in parallel** - utilizes multiple CPU cores
3. **Mock external services** - don't make real API calls
4. **Keep tests focused** - test one thing at a time
5. **Use database transactions** - faster than truncating

## Next Steps

After setup:

1. ✅ Run baseline tests: `php artisan test --testsuite=Baseline`
2. ✅ Verify all tests pass
3. ✅ Review coverage: `php artisan test --coverage`
4. Begin restructuring with confidence!

## Getting Help

If tests fail:

1. Read the error message carefully
2. Check `tests/README.md` for common issues
3. Review specific test file for context
4. Check model factories are complete
5. Verify migrations are up to date

## Contact

For restructuring questions, see `docs/restructure-plan.md`.
