<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\ProgressTests;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Progress Tests Request
 *
 * Retrieves progress tests from FlightLogger
 */
class GetProgressTestsRequest extends GraphQLRequest
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
        query ProgressTests(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          progressTests(
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
            'name',
            'from',
            'to',
            'instructor {
              id
              firstName
              lastName
            }',
            'student {
              id
              firstName
              lastName
            }',
            'result',
            'grade',
            'comments',
        ];
    }
}
