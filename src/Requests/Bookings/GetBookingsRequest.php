<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Bookings Request
 *
 * Retrieves bookings/scheduling from FlightLogger within a timespan
 */
class GetBookingsRequest extends GraphQLRequest
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
        $fieldsString = $this->buildFieldsString($this->fields);

        return <<<GQL
        query Bookings(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          bookings(
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
            '... on SingleStudentBooking {
              id
              from
              to
              aircraft {
                id
                callSign
                registration
              }
              instructor {
                id
                firstName
                lastName
              }
              student {
                id
                firstName
                lastName
              }
            }',
            '... on MultiStudentBooking {
              id
              from
              to
              aircraft {
                id
                callSign
                registration
              }
              instructor {
                id
                firstName
                lastName
              }
            }',
            '... on ExamBooking {
              id
              from
              to
              examiner {
                id
                firstName
                lastName
              }
            }',
        ];
    }
}
