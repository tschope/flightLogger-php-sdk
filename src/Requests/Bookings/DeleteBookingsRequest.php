<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Delete Bookings Request
 *
 * Deletes multiple bookings from FlightLogger
 */
class DeleteBookingsRequest extends GraphQLMutation
{
    protected array $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation DeleteBookings($ids: [Id!]!) {
          deleteBookings(ids: $ids) {
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
