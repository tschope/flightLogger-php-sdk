<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Users;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get User Request
 *
 * Retrieves a single user by ID from FlightLogger
 */
class GetUserRequest extends GraphQLRequest
{
    protected string $userId;

    protected array $fields;

    public function __construct(string $userId, ?array $fields = null)
    {
        $this->userId = $userId;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = implode("\n        ", $this->fields);

        return <<<GQL
        query User(\$id: String) {
          user(id: \$id) {
            {$fieldsString}
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return [
            'id' => $this->userId,
        ];
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
            'address',
            'city',
            'country',
            'createdAt',
            'updatedAt',
        ];
    }
}
