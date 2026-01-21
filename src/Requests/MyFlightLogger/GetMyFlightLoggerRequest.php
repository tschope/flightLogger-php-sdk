<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\MyFlightLogger;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get MyFlightLogger Request
 *
 * Retrieves FlightLogger data for the authenticated user
 */
class GetMyFlightLoggerRequest extends GraphQLRequest
{
    protected array $fields;

    public function __construct(?array $fields = null)
    {
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = implode("\n        ", $this->fields);

        return <<<GQL
        query MyFlightLogger {
          myFlightLogger {
            {$fieldsString}
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return [];
    }

    protected function getDefaultFields(): array
    {
        return [
            'id',
            'user {
              id
              firstName
              lastName
              email
              phone
              role
            }',
            'account {
              id
              name
            }',
            'settings',
            'preferences',
        ];
    }
}
