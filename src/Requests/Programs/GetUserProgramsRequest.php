<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Programs;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get User Programs Request
 *
 * Retrieves programs associated with users from FlightLogger
 */
class GetUserProgramsRequest extends GraphQLRequest
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
        query UserPrograms(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
          \$userIds: [Id!]
        ) {
          userPrograms(
            after: \$after
            all: \$all
            before: \$before
            changedAfter: \$changedAfter
            first: \$first
            from: \$from
            last: \$last
            to: \$to
            userIds: \$userIds
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
            'program {
              id
              name
              description
            }',
            'user {
              id
              firstName
              lastName
              email
            }',
            'startDate',
            'endDate',
            'status',
        ];
    }
}
