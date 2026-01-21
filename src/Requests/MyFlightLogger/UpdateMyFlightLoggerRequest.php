<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\MyFlightLogger;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Update MyFlightLogger Request
 *
 * Updates FlightLogger settings/data for the authenticated user
 */
class UpdateMyFlightLoggerRequest extends GraphQLMutation
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation UpdateMyFlightLogger($data: MyFlightLoggerInput!) {
          myFlightLogger(data: $data) {
            id
            settings
            preferences
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['data' => $this->data];
    }
}
