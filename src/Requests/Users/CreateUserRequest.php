<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Users;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create User Request
 *
 * Creates a new user in FlightLogger
 */
class CreateUserRequest extends GraphQLMutation
{
    protected array $user;

    public function __construct(array $user)
    {
        $this->user = $user;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation CreateUser(\$user: UserInput!) {
          createUser(user: \$user) {
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
        return ['user' => $this->user];
    }
}
