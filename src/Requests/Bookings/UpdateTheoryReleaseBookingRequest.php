<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

class UpdateTheoryReleaseBookingRequest extends GraphQLMutation
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
        mutation UpdateTheoryReleaseBooking(\$id: Id!, \$booking: TheoryReleaseBookingInput!) {
          updateTheoryReleaseBooking(id: \$id, booking: \$booking) {
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
