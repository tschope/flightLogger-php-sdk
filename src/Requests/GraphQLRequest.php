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
}
