<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Cancel Bookings Request
 *
 * Cancels multiple bookings in FlightLogger
 */
class CancelBookingsRequest extends GraphQLMutation
{
    protected array $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation CancelBookings(\$ids: [Id!]!) {
          cancelBookings(ids: \$ids) {
            id
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['ids' => $this->ids];
    }
}
