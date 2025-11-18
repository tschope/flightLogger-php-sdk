<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Versions;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Versions Request
 *
 * Retrieves API versions from FlightLogger
 */
class GetVersionsRequest extends GraphQLRequest
{
    protected array $fields;

    public function __construct(array $fields = null)
    {
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = $this->buildFieldsString($this->fields);

        return <<<GQL
        query Versions {
          versions {
            {$fieldsString}
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return [];
    }

    protected function getDefaultFields(): array
    {
        return [
            'api',
            'schema',
        ];
    }
}
