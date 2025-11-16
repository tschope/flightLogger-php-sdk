# FlightLogger PHP SDK

A PHP SDK for integrating with FlightLogger's GraphQL API, built using Saloon.

## Description

This package simplifies integration with the FlightLogger API, allowing you to fetch information about users, students, classes, flights, and training in a simple and intuitive way.

## Installation

You can install the package via Composer:

```bash
composer require tschope/flightlogger-php
```

## Requirements

- PHP 8.1 or higher
- cURL extension enabled

## Configuration

### Basic Configuration (Pure PHP)

First, you need to obtain your API token from FlightLogger. Then, you can initialize the connector in three ways:

#### 1. Passing the token directly to the constructor

```php
<?php

use Tschope\FlightLogger\FlightLoggerConnector;

$connector = new FlightLoggerConnector('your-api-token-here');
```

#### 2. Using environment variable

Add to your `.env` file:

```env
FLIGHTLOGGER_API_TOKEN=your-api-token-here
```

Then initialize without passing the token:

```php
<?php

use Tschope\FlightLogger\FlightLoggerConnector;

// Token will be automatically read from environment variable
$connector = new FlightLoggerConnector();
```

### Laravel Configuration

The package has automatic integration with Laravel through auto-discovery.

#### 1. Publish the configuration file (optional)

```bash
php artisan vendor:publish --tag=flightlogger-config
```

This will create the `config/flightlogger.php` file.

#### 2. Configure in `.env`

Add to your `.env` file:

```env
FLIGHTLOGGER_API_TOKEN=your-api-token-here
```

#### 3. Use via Dependency Injection

In Laravel, you can inject the connector directly into your controllers or services:

```php
<?php

namespace App\Http\Controllers;

use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Users\GetUsersRequest;

class FlightLoggerController extends Controller
{
    public function __construct(
        protected FlightLoggerConnector $flightLogger
    ) {}

    public function index()
    {
        $request = new GetUsersRequest(['limit' => 10]);
        $response = $this->flightLogger->send($request);

        return response()->json($response->json());
    }
}
```

#### 4. Or use Laravel's helper

```php
use Tschope\FlightLogger\FlightLoggerConnector;

$connector = app(FlightLoggerConnector::class);
```

### Configuration Priority

The package looks for the API token in the following order:

1. **Token passed to constructor** (highest priority)
2. **Laravel configuration file** (`config/flightlogger.php`)
3. **Environment variable** `FLIGHTLOGGER_API_TOKEN`

If no token is found, a `RuntimeException` will be thrown.

## Usage

### Fetching Users

#### List all users

```php
use Tschope\FlightLogger\Requests\Users\GetUsersRequest;

$request = new GetUsersRequest([
    'limit' => 50,
    'offset' => 0,
    'orderBy' => 'firstName',
    'orderDirection' => 'ASC'
]);

$response = $connector->send($request);
$data = $response->json();

// Accessing data
foreach ($data['data']['users']['edges'] as $edge) {
    $user = $edge['node'];
    echo "Name: {$user['firstName']} {$user['lastName']}\n";
    echo "Email: {$user['email']}\n";
}
```

#### Fetch a specific user

```php
use Tschope\FlightLogger\Requests\Users\GetUserRequest;

$request = new GetUserRequest('user-id-here');
$response = $connector->send($request);
$user = $response->json()['data']['user'];

echo "Name: {$user['firstName']} {$user['lastName']}\n";
```

### Fetching Classes (Groups of Students)

```php
use Tschope\FlightLogger\Requests\Classes\GetClassesRequest;

$request = new GetClassesRequest([
    'first' => 20
]);

$response = $connector->send($request);
$data = $response->json();

foreach ($data['data']['classes']['edges'] as $edge) {
    $class = $edge['node'];
    echo "Class: {$class['name']}\n";

    // Listing students in the class
    foreach ($class['users'] as $student) {
        echo "  - {$student['firstName']} {$student['lastName']}\n";
    }
}
```

### Fetching Flights

```php
use Tschope\FlightLogger\Requests\Flights\GetFlightsRequest;

$request = new GetFlightsRequest([
    'first' => 50,
    'from' => '2024-01-01T00:00:00Z',
    'to' => '2024-12-31T23:59:59Z'
]);

$response = $connector->send($request);
$data = $response->json();

foreach ($data['data']['flights']['edges'] as $edge) {
    $flight = $edge['node'];
    echo "Flight: {$flight['id']}\n";
    echo "Type: {$flight['flightType']}\n";
    echo "Aircraft: {$flight['aircraft']['registration']}\n";
    echo "From: {$flight['departureAirport']['icao']} to {$flight['arrivalAirport']['icao']}\n";

    // Pilot/instructor information
    if (!empty($flight['primaryLog'])) {
        $pilot = $flight['primaryLog']['user'];
        echo "Pilot: {$pilot['firstName']} {$pilot['lastName']}\n";
    }

    echo "\n";
}
```

### Fetching Trainings

```php
use Tschope\FlightLogger\Requests\Trainings\GetTrainingsRequest;

$request = new GetTrainingsRequest([
    'first' => 30,
    'status' => ['APPROVED', 'PENDING']
]);

$response = $connector->send($request);
$data = $response->json();

foreach ($data['data']['trainings']['edges'] as $edge) {
    $training = $edge['node'];
    echo "Training: {$training['name']}\n";
    echo "Status: {$training['status']}\n";

    // Instructor information
    if (!empty($training['instructor'])) {
        echo "Instructor: {$training['instructor']['firstName']} {$training['instructor']['lastName']}\n";
    }

    // Student information
    if (!empty($training['student'])) {
        echo "Student: {$training['student']['firstName']} {$training['student']['lastName']}\n";
    }

    // Total time
    $hours = $training['totalSeconds'] / 3600;
    echo "Total hours: " . number_format($hours, 2) . "h\n";

    echo "\n";
}
```

## Custom Fields

You can customize the fields returned by queries by passing an array of fields as the second parameter:

```php
$request = new GetUsersRequest(
    ['limit' => 10],
    ['id', 'firstName', 'lastName', 'email', 'phone']
);
```

For nested fields (like relationships), use GraphQL syntax:

```php
$request = new GetFlightsRequest(
    ['first' => 10],
    [
        'id',
        'flightType',
        'aircraft {
          registration
          model
          type
        }',
        'primaryLog {
          user {
            firstName
            lastName
          }
          role
        }'
    ]
);
```

## Pagination

FlightLogger queries use cursor-based pagination. You can navigate through results using the `after`, `before`, `first`, and `last` parameters:

```php
// First page
$request = new GetFlightsRequest(['first' => 20]);
$response = $connector->send($request);
$data = $response->json();

// Check if there's a next page
if ($data['data']['flights']['pageInfo']['hasNextPage']) {
    $endCursor = $data['data']['flights']['pageInfo']['endCursor'];

    // Fetch next page
    $nextRequest = new GetFlightsRequest([
        'first' => 20,
        'after' => $endCursor
    ]);
    $nextResponse = $connector->send($nextRequest);
}
```

## Error Handling

```php
try {
    $response = $connector->send($request);

    if ($response->failed()) {
        echo "Request error: " . $response->status() . "\n";
        echo $response->body();
    } else {
        $data = $response->json();
        // Process data
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Contributing

Contributions are welcome! This package was created to start with the most common endpoints, but the FlightLogger API has many other available endpoints.

To add new endpoints:

1. Create a new class that extends `GraphQLRequest` or `GraphQLMutation`
2. Implement the `getQuery()` (or `getMutation()`) and `getVariables()` methods
3. Define default fields in `getDefaultFields()`
4. Add tests and documentation

Example structure for a new endpoint:

```php
<?php

namespace Tschope\FlightLogger\Requests\ResourceName;

use Tschope\FlightLogger\Requests\GraphQLRequest;

class GetResourceRequest extends GraphQLRequest
{
    protected array $filters;
    protected array $fields;

    public function __construct(array $filters = [], array $fields = null)
    {
        $this->filters = $filters;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        // Your GraphQL query here
    }

    protected function getVariables(): array
    {
        return $this->filters;
    }

    protected function getDefaultFields(): array
    {
        // Default query fields
    }
}
```

## License

This package is open-source and available under the MIT license.

## Links

- [FlightLogger API Documentation](https://api.flightlogger.net/)
- [Saloon HTTP Client](https://docs.saloon.dev/)
- [GitHub Repository](https://github.com/tschope/flightlogger-php)

## Support

If you encounter any issues or have suggestions, please open an issue on GitHub.

## Credits

Developed by [tschope](https://github.com/tschope)

This is an unofficial package and is not affiliated with FlightLogger.
