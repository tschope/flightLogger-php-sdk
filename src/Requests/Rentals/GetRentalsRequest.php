<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Rentals;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Rentals Request
 *
 * Retrieves aircraft rentals from FlightLogger
 */
class GetRentalsRequest extends GraphQLRequest
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
        query Rentals(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          rentals(
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
            'from',
            'to',
            'aircraft {
              id
              callSign
              registration
            }',
            'renter {
              id
              firstName
              lastName
            }',
            'purpose',
            'remarks',
        ];
    }
}
