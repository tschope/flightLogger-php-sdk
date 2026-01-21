<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Exam Booking Request
 *
 * Creates a new exam booking in FlightLogger
 */
class CreateExamBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation CreateExamBooking($booking: ExamBookingInput!) {
          createExamBooking(booking: $booking) {
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
