<?php

declare(strict_types=1);

namespace Tschope\FlightLogger;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use RuntimeException;

/**
 * FlightLogger API Connector
 *
 * Base connector for the FlightLogger GraphQL API
 */
class FlightLoggerConnector extends Connector
{
    use AcceptsJson;

    protected string $apiToken;

    /**
     * Create a new FlightLogger connector instance
     *
     * @param string|null $apiToken API token (optional if FLIGHTLOGGER_API_TOKEN env var is set)
     * @throws RuntimeException if no token is provided and env var is not set
     */
    public function __construct(?string $apiToken = null)
    {
        $this->apiToken = $this->resolveApiToken($apiToken);
    }

    /**
     * Resolve the API token from parameter or environment
     *
     * @param string|null $apiToken
     * @return string
     * @throws RuntimeException
     */
    protected function resolveApiToken(?string $apiToken): string
    {
        // If token is provided directly, use it
        if ($apiToken !== null) {
            return $apiToken;
        }

        // Try to get from Laravel config (if available)
        if (function_exists('config')) {
            $configToken = config('flightlogger.api_token');
            if ($configToken !== null) {
                return $configToken;
            }
        }

        // Try to get from environment variable
        $envToken = getenv('FLIGHTLOGGER_API_TOKEN') ?: ($_ENV['FLIGHTLOGGER_API_TOKEN'] ?? null);
        if ($envToken !== null && $envToken !== '') {
            return $envToken;
        }

        throw new RuntimeException(
            'FlightLogger API token not found. Please provide a token via constructor, ' .
            'set FLIGHTLOGGER_API_TOKEN environment variable, or configure it in config/flightlogger.php'
        );
    }

    /**
     * Define the base URL for the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.flightlogger.net';
    }

    /**
     * Define default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
