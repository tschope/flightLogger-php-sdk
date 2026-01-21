<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\DutyTimes;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Duty Times Request
 *
 * Retrieves duty times/work hours from FlightLogger
 */
class GetDutyTimesRequest extends GraphQLRequest
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
        query DutyTimes(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          dutyTimes(
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
            'user {
              id
              firstName
              lastName
            }',
            'dutyType',
            'remarks',
        ];
    }
}
