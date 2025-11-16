<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Update Class Theory Booking Request
 *
 * Updates an existing class theory booking in FlightLogger
 */
class UpdateClassTheoryBookingRequest extends GraphQLMutation
{
    protected string $id;
    protected array $booking;

    public function __construct(string $id, array $booking)
    {
        $this->id = $id;
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation UpdateClassTheoryBooking(\$id: Id!, \$booking: ClassTheoryBookingInput!) {
          updateClassTheoryBooking(id: \$id, booking: \$booking) {
            id
            from
            to
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return ['id' => $this->id, 'booking' => $this->booking];
    }
}
