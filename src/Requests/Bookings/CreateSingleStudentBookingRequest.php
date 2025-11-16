<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Single Student Booking Request
 *
 * Creates a new single student booking in FlightLogger
 */
class CreateSingleStudentBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation CreateSingleStudentBooking(\$booking: SingleStudentBookingInput!) {
          createSingleStudentBooking(booking: \$booking) {
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
