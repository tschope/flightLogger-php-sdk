<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Maintenance Booking Request
 *
 * Creates a new maintenance booking in FlightLogger
 */
class CreateMaintenanceBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<'GQL'
        mutation CreateMaintenanceBooking($booking: MaintenanceBookingInput!) {
          createMaintenanceBooking(booking: $booking) {
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
