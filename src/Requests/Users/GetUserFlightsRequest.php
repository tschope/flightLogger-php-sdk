<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Users;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get User Flights Request
 *
 * Retrieves flights for a specific user from FlightLogger
 */
class GetUserFlightsRequest extends GraphQLRequest
{
    protected string $userId;

    protected array $flightFilters;

    protected array $flightFields;

    public function __construct(string $userId, array $flightFilters = [], ?array $flightFields = null)
    {
        $this->userId = $userId;
        $this->flightFilters = $flightFilters;
        $this->flightFields = $flightFields ?? $this->getDefaultFlightFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = $this->buildFieldsString($this->flightFields);

        // Build flight filters parameters
        $filterParams = [];
        if (isset($this->flightFilters['from'])) {
            $filterParams[] = 'from: $from';
        }
        if (isset($this->flightFilters['to'])) {
            $filterParams[] = 'to: $to';
        }
        if (isset($this->flightFilters['all'])) {
            $filterParams[] = 'all: $all';
        }
        if (isset($this->flightFilters['last'])) {
            $filterParams[] = 'last: $last';
        }
        if (isset($this->flightFilters['first'])) {
            $filterParams[] = 'first: $first';
        }

        $filterParamsString = implode(', ', $filterParams);

        // Build variable declarations
        $variableDeclarations = ['$userId: String!'];
        if (isset($this->flightFilters['from'])) {
            $variableDeclarations[] = '$from: DateTime';
        }
        if (isset($this->flightFilters['to'])) {
            $variableDeclarations[] = '$to: DateTime';
        }
        if (isset($this->flightFilters['all'])) {
            $variableDeclarations[] = '$all: Boolean';
        }
        if (isset($this->flightFilters['last'])) {
            $variableDeclarations[] = '$last: Int';
        }
        if (isset($this->flightFilters['first'])) {
            $variableDeclarations[] = '$first: Int';
        }

        $variableDeclarationsString = implode(', ', $variableDeclarations);

        return <<<GQL
        query UserFlights({$variableDeclarationsString}) {
          user(id: \$userId) {
            id
            firstName
            lastName
            flights({$filterParamsString}) {
              nodes {
                {$fieldsString}
              }
            }
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        $variables = [
            'userId' => $this->userId,
        ];

        // Add flight filter variables
        foreach ($this->flightFilters as $key => $value) {
            $variables[$key] = $value;
        }

        return $variables;
    }

    protected function getDefaultFlightFields(): array
    {
        return [
            'id',
            'flightType',
            'offBlock',
            'onBlock',
            'takeoff',
            'landing',
            'atSeconds',
            'ifSeconds',
            'ifrSeconds',
            'vfrSeconds',
            'daySeconds',
            'nightSeconds',
            'localSeconds',
            'crossCountrySeconds',
            'pilotFlyingSeconds',
            'pilotMonitoringSeconds',
            'timerStartSeconds',
            'timerFinishSeconds',
            'aircraft { id model callSign aircraftClass aircraftType defaultEngineType }',
            'departureAirport { id name }',
            'arrivalAirport { id name }',
            'landings { __typename ... on Landing { id isArrival landingType landingTypeCount nightLanding } }',
            'activityRegistration { __typename ... on Training { id name instructor { id firstName lastName } } ... on Rental { id } ... on Operation { id pic { id firstName lastName } } }',
        ];
    }
}
