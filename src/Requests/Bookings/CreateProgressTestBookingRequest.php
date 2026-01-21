<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Progress Test Booking Request
 *
 * Creates a new progress test booking in FlightLogger
 */
class CreateProgressTestBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation CreateProgressTestBooking($booking: ProgressTestBookingInput!) {
          createProgressTestBooking(booking: $booking) {
            id
            from
            to
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['booking' => $this->booking];
    }
}
