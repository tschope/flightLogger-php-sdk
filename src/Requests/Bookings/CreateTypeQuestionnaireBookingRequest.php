<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Bookings;

use Tschope\FlightLogger\Requests\GraphQLMutation;

/**
 * Create Type Questionnaire Booking Request
 *
 * Creates a new type questionnaire booking in FlightLogger
 */
class CreateTypeQuestionnaireBookingRequest extends GraphQLMutation
{
    protected array $booking;

    public function __construct(array $booking)
    {
        $this->booking = $booking;
    }

    protected function getMutation(): string
    {
        return <<<GQL
        mutation CreateTypeQuestionnaireBooking(\$booking: TypeQuestionnaireBookingInput!) {
          createTypeQuestionnaireBooking(booking: \$booking) {
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
