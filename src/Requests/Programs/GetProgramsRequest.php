<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Programs;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Programs Request
 *
 * Retrieves active training programs from FlightLogger
 */
class GetProgramsRequest extends GraphQLRequest
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
        query Programs(
          \$after: String
          \$before: String
          \$first: Int
          \$last: Int
          \$programIds: [Id!]
          \$programName: String
        ) {
          programs(
            after: \$after
            before: \$before
            first: \$first
            last: \$last
            programIds: \$programIds
            programName: \$programName
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
            'description',
            'active',
            'color',
        ];
    }
}
