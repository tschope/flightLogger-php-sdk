<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

class UpdateMaintenanceBookingRequest extends GraphQLMutation
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
        mutation UpdateMaintenanceBooking(\$id: Id!, \$booking: MaintenanceBookingInput!) {
          updateMaintenanceBooking(id: \$id, booking: \$booking) {
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
