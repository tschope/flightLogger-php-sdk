<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Theory Release Booking Request
 *
 * Creates a new theory release booking in FlightLogger
 */
class CreateTheoryReleaseBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation CreateTheoryReleaseBooking(\$booking: TheoryReleaseBookingInput!) {
          createTheoryReleaseBooking(booking: \$booking) {
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
