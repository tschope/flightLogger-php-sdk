<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Classes;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Classes Request
 *
 * Retrieves classes (groups of students) from FlightLogger
 */
class GetClassesRequest extends GraphQLRequest
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
        query Classes(
          \$after: String
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$last: Int
        ) {
          classes(
            after: \$after
            before: \$before
            changedAfter: \$changedAfter
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
            'name',
            'users {
              id
              firstName
              lastName
              email
            }',
        ];
    }
}
