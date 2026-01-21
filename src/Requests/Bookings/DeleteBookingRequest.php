<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Delete Booking Request
 *
 * Deletes a single booking from FlightLogger
 */
class DeleteBookingRequest extends GraphQLMutation
{
    protected string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation DeleteBooking($id: Id!) {
          deleteBooking(id: $id) {
            id
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['id' => $this->id];
    }
}
