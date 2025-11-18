<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Trainings;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Trainings Request
 *
 * Retrieves trainings/courses from FlightLogger with details about instructor, student, flights, and more
 */
class GetTrainingsRequest extends GraphQLRequest
{
    protected array $filters;
    protected array $fields;

    public function __construct(array $filters = [], array $fields = null)
    {
        $this->filters = $filters;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = $this->buildFieldsString($this->fields);

        return <<<GQL
        query Trainings(
          \$after: String
          \$all: Boolean
          \$before: String
          \$changedAfter: DateTime
          \$first: Int
          \$from: DateTime
          \$last: Int
          \$programIds: [Id!]
          \$status: [TrainingStatusEnum!]
          \$to: DateTime
          \$userIds: [Id!]
        ) {
          trainings(
            after: \$after
            all: \$all
            before: \$before
            changedAfter: \$changedAfter
            first: \$first
            from: \$from
            last: \$last
            programIds: \$programIds
            status: \$status
            to: \$to
            userIds: \$userIds
          ) {
            edges {
              cursor
              node {
                {$fieldsString}
              }
            }
            pageInfo {
              endCursor
              hasNextPage
              hasPreviousPage
              startCursor
            }
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return $this->filters;
    }

    protected function getDefaultFields(): array
    {
        return [
            'id',
            'name',
            'status',
            'totalSeconds',
            'briefingSeconds',
            'debriefingSeconds',
            'comment',
            'instructor {
              id
              firstName
              lastName
              email
            }',
            'student {
              id
              firstName
              lastName
              email
            }',
            'flights {
              id
              flightType
              offBlock
              onBlock
              aircraft {
                id
                registration
              }
            }',
            'nightSeconds',
            'ifrDualSeconds',
            'ifrSimSeconds',
            'vfrDualSeconds',
            'vfrSimSeconds',
            'vfrSoloSeconds',
            'pilotFlyingSeconds',
            'pilotMonitoringSeconds',
            'approvedByStudent',
            'approvedByStudentAt',
            'submittedByInstructorAt',
        ];
    }
}
