<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Users;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Users Request
 *
 * Retrieves a list of users from FlightLogger
 */
class GetUsersRequest extends GraphQLRequest
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
        query Users(
          \$accountId: String
          \$limit: Int
          \$offset: Int
          \$orderBy: String
          \$orderDirection: String
          \$roles: [UserRole!]
          \$first: Int
          \$after: String
        ) {
          users(
            accountId: \$accountId
            limit: \$limit
            offset: \$offset
            orderBy: \$orderBy
            orderDirection: \$orderDirection
            roles: \$roles
            first: \$first
            after: \$after
          ) {
            nodes {
              {$fieldsString}
            }
            edges {
              node {
                {$fieldsString}
              }
            }
            pageInfo {
              hasNextPage
              hasPreviousPage
              endCursor
            }
            totalCount
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
            'firstName',
            'lastName',
            'email',
            'phone',
            'role',
            'status',
            'createdAt',
            'updatedAt',
        ];
    }
}
