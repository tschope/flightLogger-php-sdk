<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base GraphQL Request
 *
 * All GraphQL requests extend from this class
 */
abstract class GraphQLRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * Define the endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/graphql';
    }

    /**
     * Get the GraphQL query string
     */
    abstract protected function getQuery(): string;

    /**
     * Get the GraphQL variables
     */
    protected function getVariables(): array
    {
        return [];
    }

    /**
     * Define the body for the request
     */
    protected function defaultBody(): array
    {
        return [
            'query' => $this->getQuery(),
            'variables' => $this->getVariables(),
        ];
    }

    /**
     * Build fields string, handling nested structures properly
     *
     * This method converts an array of field definitions into a properly formatted
     * GraphQL fields string, supporting nested structures like "contact { email phone }".
     *
     * @param  array  $fields  Array of field definitions
     * @param  int  $indent  Indentation level for nested fields
     * @return string Formatted fields string for GraphQL query
     */
    protected function buildFieldsString(array $fields, int $indent = 0): string
    {
        $lines = [];
        $indentation = str_repeat('  ', $indent);

        foreach ($fields as $field) {
            // Check if field contains nested structure
            if (str_contains($field, '{')) {
                // If field already has complete structure (contains both { and }), use it as-is
                if (str_contains($field, '}')) {
                    $lines[] = $indentation.$field;
                } else {
                    // Legacy handling for incomplete structures
                    $parts = explode('{', $field, 2);
                    $fieldName = trim($parts[0]);
                    $nestedContent = trim(str_replace('}', '', $parts[1]));

                    $lines[] = $indentation.$fieldName.' {';
                    $lines[] = $indentation.'  '.$nestedContent;
                    $lines[] = $indentation.'}';
                }
            } else {
                $lines[] = $indentation.$field;
            }
        }

        return implode("\n", $lines);
    }
}
