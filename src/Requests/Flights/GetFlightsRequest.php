<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Flights;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Flights Request
 *
 * Retrieves flights from FlightLogger with details about aircraft, instructors, students, and more
 */
class GetFlightsRequest extends GraphQLRequest
{
    protected array $filters;

    protected array $fields;

    public function __construct(array $filters = [], ?array $fields = null)
    {
        $this->filters = $filters;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = $this->buildFieldsString($this->fields);

        return <<<GQL
        query Flights(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          flights(
            after: \$after
            all: \$all
            before: \$before
            changedAfter: \$changedAfter
            first: \$first
            from: \$from
            last: \$last
            to: \$to
          ) {
            edges {
              cursor
              node {
                {$fieldsString}
              }
            }
            pageInfo {
              endCursor
              hasNextPage
              hasPreviousPage
              startCursor
            }
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return $this->filters;
    }

    protected function getDefaultFields(): array
    {
        return [
            'id',
            'flightType',
            'offBlock',
            'onBlock',
            'takeoff',
            'landing',
            'calculatedFuelUsage',
            'departureFuel',
            'departureFuelAdded',
            'aircraft {
              id
              registration
              model
              type
            }',
            'departureAirport {
              icao
              name
            }',
            'arrivalAirport {
              icao
              name
            }',
            'primaryLog {
              id
              user {
                id
                firstName
                lastName
                email
              }
              role
            }',
            'secondaryLog {
              id
              user {
                id
                firstName
                lastName
                email
              }
              role
            }',
            'daySeconds',
            'nightSeconds',
            'ifrSeconds',
            'vfrSeconds',
            'pilotFlyingSeconds',
            'pilotMonitoringSeconds',
        ];
    }
}
