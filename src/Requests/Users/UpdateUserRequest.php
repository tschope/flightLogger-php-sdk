<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Users;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Update User Request
 *
 * Updates an existing user in FlightLogger
 */
class UpdateUserRequest extends GraphQLMutation
{
    protected string $id;
    protected array $user;

    public function __construct(string $id, array $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation UpdateUser(\$id: Id!, \$user: UserInput!) {
          updateUser(id: \$id, user: \$user) {
            id
            firstName
            lastName
            email
            role
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['id' => $this->id, 'user' => $this->user];
    }
}
