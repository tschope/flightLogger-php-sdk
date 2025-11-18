<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Maintenance;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Maintenance Types Request
 *
 * Retrieves maintenance types from FlightLogger
 */
class GetMaintenanceTypesRequest extends GraphQLRequest
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
        query MaintenanceTypes(
          \$after: String
          \$before: String
          \$first: Int
          \$last: Int
        ) {
          maintenanceTypes(
            after: \$after
            before: \$before
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
            'description',
            'category',
        ];
    }
}
