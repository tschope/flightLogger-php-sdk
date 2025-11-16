<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Base GraphQL Mutation
 *
 * All GraphQL mutations extend from this class
 */
abstract class GraphQLMutation extends Request implements HasBody
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
     * Get the GraphQL mutation string
     */
    abstract protected function getMutation(): string;

    /**
     * Get the GraphQL variables
     */
    abstract protected function getVariables(): array;

    /**
     * Define the body for the request
     */
    protected function defaultBody(): array
    {
        return [
            'query' => $this->getMutation(),
            'variables' => $this->getVariables(),
        ];
    }
}
