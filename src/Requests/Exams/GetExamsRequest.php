<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Exams;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Exams Request
 *
 * Retrieves exams from FlightLogger
 */
class GetExamsRequest extends GraphQLRequest
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
        query Exams(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$to: DateTime
        ) {
          exams(
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
            'examType',
            'examiner {
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
        ];
    }
}
