<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Aircraft;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Aircraft Request
 *
 * Retrieves active aircraft from FlightLogger
 */
class GetAircraftRequest extends GraphQLRequest
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
        $fieldsString = implode("\n            ", $this->fields);

        return <<<GQL
        query Aircraft(
          \$after: String
          \$before: String
          \$callSigns: [String!]
          \$first: Int
          \$last: Int
        ) {
          aircraft(
            after: \$after
            before: \$before
            callSigns: \$callSigns
            first: \$first
            last: \$last
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
            'callSign',
            'registration',
            'model',
            'type',
            'category',
            'fuelType',
            'homeBase',
            'year',
            'serialNumber',
            'seats',
            'mtow',
            'remarks',
        ];
    }
}
