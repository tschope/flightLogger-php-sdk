<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Users\GetUsersRequest;
use Tschope\FlightLogger\Requests\Users\GetUserRequest;
use Tschope\FlightLogger\Requests\Classes\GetClassesRequest;
use Tschope\FlightLogger\Requests\Flights\GetFlightsRequest;
use Tschope\FlightLogger\Requests\Trainings\GetTrainingsRequest;

// Initialize the connector
// Option 1: Pass token directly
// $connector = new FlightLoggerConnector('your-api-token-here');

// Option 2: Use environment variable FLIGHTLOGGER_API_TOKEN
// The connector will automatically read from .env or environment
$connector = new FlightLoggerConnector();

echo "=== FlightLogger PHP SDK - Examples ===\n\n";

// Example 1: Get Users
echo "1. Getting users...\n";
try {
    $request = new GetUsersRequest([
        'limit' => 5,
        'orderBy' => 'firstName'
    ]);

    $response = $connector->send($request);

    if ($response->successful()) {
        $data = $response->json();
        $users = $data['data']['users']['edges'] ?? [];

        echo "Found " . count($users) . " users:\n";
        foreach ($users as $edge) {
            $user = $edge['node'];
            echo "  - {$user['firstName']} {$user['lastName']} ({$user['email']})\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 2: Get Classes
echo "2. Getting classes...\n";
try {
    $request = new GetClassesRequest([
        'first' => 5
    ]);

    $response = $connector->send($request);

    if ($response->successful()) {
        $data = $response->json();
        $classes = $data['data']['classes']['edges'] ?? [];

        echo "Found " . count($classes) . " classes:\n";
        foreach ($classes as $edge) {
            $class = $edge['node'];
            echo "  - {$class['name']} (" . count($class['users'] ?? []) . " students)\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 3: Get Flights with date filter
echo "3. Getting recent flights...\n";
try {
    $request = new GetFlightsRequest([
        'first' => 5,
        'from' => date('Y-m-d', strtotime('-30 days')) . 'T00:00:00Z'
    ]);

    $response = $connector->send($request);

    if ($response->successful()) {
        $data = $response->json();
        $flights = $data['data']['flights']['edges'] ?? [];

        echo "Found " . count($flights) . " flights:\n";
        foreach ($flights as $edge) {
            $flight = $edge['node'];
            $registration = $flight['aircraft']['registration'] ?? 'N/A';
            echo "  - Flight {$flight['id']} - {$registration} ({$flight['flightType']})\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 4: Get Trainings with custom fields
echo "4. Getting trainings with custom fields...\n";
try {
    $request = new GetTrainingsRequest(
        ['first' => 5],
        [
            'id',
            'name',
            'status',
            'totalSeconds',
            'instructor {
              firstName
              lastName
            }',
            'student {
              firstName
              lastName
            }'
        ]
    );

    $response = $connector->send($request);

    if ($response->successful()) {
        $data = $response->json();
        $trainings = $data['data']['trainings']['edges'] ?? [];

        echo "Found " . count($trainings) . " trainings:\n";
        foreach ($trainings as $edge) {
            $training = $edge['node'];
            $hours = ($training['totalSeconds'] ?? 0) / 3600;
            echo "  - {$training['name']} - {$training['status']} (" .
                 number_format($hours, 2) . "h)\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Example 5: Pagination
echo "5. Demonstrating pagination...\n";
try {
    // First page
    $request = new GetUsersRequest(['first' => 2]);
    $response = $connector->send($request);

    if ($response->successful()) {
        $data = $response->json();
        $pageInfo = $data['data']['users']['pageInfo'] ?? [];

        echo "First page - Has next page: " .
             ($pageInfo['hasNextPage'] ? 'Yes' : 'No') . "\n";

        if ($pageInfo['hasNextPage']) {
            // Second page
            $nextRequest = new GetUsersRequest([
                'first' => 2,
                'after' => $pageInfo['endCursor']
            ]);
            $nextResponse = $connector->send($nextRequest);

            if ($nextResponse->successful()) {
                echo "Successfully fetched second page\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Examples completed ===\n";
