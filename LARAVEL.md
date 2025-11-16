# Laravel Integration

This package has complete integration with Laravel through auto-discovery.

## Installation

```bash
composer require tschope/flightlogger-php
```

The Service Provider will be registered automatically.

## Configuration

### 1. Publish configuration file (optional)

```bash
php artisan vendor:publish --tag=flightlogger-config
```

This will create the `config/flightlogger.php` file.

### 2. Add token to `.env`

```env
FLIGHTLOGGER_API_TOKEN=your-token-here
```

## Usage in Laravel

### Dependency Injection

The connector can be automatically injected into your controllers:

```php
<?php

namespace App\Http\Controllers;

use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Users\GetUsersRequest;

class FlightController extends Controller
{
    public function __construct(
        protected FlightLoggerConnector $flightLogger
    ) {}

    public function listUsers()
    {
        $request = new GetUsersRequest(['limit' => 10]);
        $response = $this->flightLogger->send($request);

        return response()->json($response->json());
    }
}
```

### Service Container

Or resolve directly from the container:

```php
$connector = app(FlightLoggerConnector::class);
```

### Facades (Optional)

If you prefer, you can create a custom facade:

```php
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Tschope\FlightLogger\FlightLoggerConnector;

class FlightLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FlightLoggerConnector::class;
    }
}
```

And use it like this:

```php
use App\Facades\FlightLogger;
use Tschope\FlightLogger\Requests\Users\GetUsersRequest;

$request = new GetUsersRequest(['limit' => 10]);
$response = FlightLogger::send($request);
```

## Practical Examples

### Fetch recent flights

```php
use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Flights\GetFlightsRequest;

class DashboardController extends Controller
{
    public function recentFlights(FlightLoggerConnector $connector)
    {
        $request = new GetFlightsRequest([
            'first' => 20,
            'from' => now()->subDays(30)->toIso8601String(),
        ]);

        $response = $connector->send($request);
        $flights = $response->json()['data']['flights']['edges'];

        return view('dashboard.flights', compact('flights'));
    }
}
```

### Create a booking

```php
use Tschope\FlightLogger\Requests\Bookings\CreateSingleStudentBookingRequest;

public function createBooking(Request $request, FlightLoggerConnector $connector)
{
    $validated = $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after:from',
        'aircraft_id' => 'required|string',
        'instructor_id' => 'required|string',
        'student_id' => 'required|string',
    ]);

    $booking = [
        'from' => $validated['from'],
        'to' => $validated['to'],
        'aircraftId' => $validated['aircraft_id'],
        'instructorId' => $validated['instructor_id'],
        'studentId' => $validated['student_id'],
    ];

    $createRequest = new CreateSingleStudentBookingRequest($booking);
    $response = $connector->send($createRequest);

    if ($response->successful()) {
        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking created successfully!');
    }

    return back()->withErrors(['error' => 'Error creating booking']);
}
```

### Use in Jobs

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Flights\GetFlightsRequest;

class SyncFlightsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(FlightLoggerConnector $connector): void
    {
        $request = new GetFlightsRequest([
            'from' => now()->subDay()->toIso8601String(),
        ]);

        $response = $connector->send($request);
        $flights = $response->json()['data']['flights']['edges'];

        // Process and save flights to local database
        foreach ($flights as $edge) {
            $flight = $edge['node'];
            // Your logic here
        }
    }
}
```

## Error Handling

```php
use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Users\GetUserRequest;
use Saloon\Exceptions\Request\RequestException;

public function getUser(string $userId, FlightLoggerConnector $connector)
{
    try {
        $request = new GetUserRequest($userId);
        $response = $connector->send($request);

        if ($response->successful()) {
            return $response->json()['data']['user'];
        }

        // Handle HTTP errors
        logger()->error('FlightLogger API error', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;

    } catch (RequestException $e) {
        // Handle network/connection exceptions
        logger()->error('FlightLogger request failed', [
            'message' => $e->getMessage(),
        ]);

        throw $e;
    }
}
```

## Request Caching

You can use Laravel's cache to optimize:

```php
use Illuminate\Support\Facades\Cache;

public function getCachedUsers(FlightLoggerConnector $connector)
{
    return Cache::remember('flightlogger.users', 3600, function () use ($connector) {
        $request = new GetUsersRequest(['limit' => 100]);
        $response = $connector->send($request);

        return $response->json()['data']['users']['edges'];
    });
}
```

## Testing

In tests, you can mock the connector:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tschope\FlightLogger\FlightLoggerConnector;
use Mockery;

class FlightLoggerTest extends TestCase
{
    public function test_can_fetch_users()
    {
        $mock = Mockery::mock(FlightLoggerConnector::class);
        $mock->shouldReceive('send')
            ->once()
            ->andReturn(/* mock response */);

        $this->app->instance(FlightLoggerConnector::class, $mock);

        // Your test here
    }
}
```
