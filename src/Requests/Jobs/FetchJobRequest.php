<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Jobs;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Fetch Job Request
 *
 * Retrieves a specific job/task status from FlightLogger
 */
class FetchJobRequest extends GraphQLRequest
{
    protected string $jobId;
    protected array $fields;

    public function __construct(string $jobId, array $fields = null)
    {
        $this->jobId = $jobId;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = implode("\n        ", $this->fields);

        return <<<GQL
        query FetchJob(\$jobId: String!) {
          fetchJob(jobId: \$jobId) {
            {$fieldsString}
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return [
            'jobId' => $this->jobId,
        ];
    }

    protected function getDefaultFields(): array
    {
        return [
            'id',
            'status',
            'progress',
            'result',
            'error',
        ];
    }
}
