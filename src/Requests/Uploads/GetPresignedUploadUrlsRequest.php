<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\Uploads;

use Tschope\FlightLogger\Requests\GraphQLRequest;

/**
 * Get Presigned Upload URLs Request
 *
 * Retrieves presigned URLs for file uploads to FlightLogger
 */
class GetPresignedUploadUrlsRequest extends GraphQLRequest
{
    protected array $files;
    protected array $fields;

    public function __construct(array $files, array $fields = null)
    {
        $this->files = $files;
        $this->fields = $fields ?? $this->getDefaultFields();
    }

    protected function getQuery(): string
    {
        $fieldsString = implode("\n        ", $this->fields);

        return <<<GQL
        query PresignedUploadUrls(\$files: [FileUploadInput!]!) {
          presignedUploadUrls(files: \$files) {
            {$fieldsString}
          }
        }
        GQL;
    }

    protected function getVariables(): array
    {
        return [
            'files' => $this->files,
        ];
    }

    protected function getDefaultFields(): array
    {
        return [
            'fileId',
            'url',
            'expiresAt',
        ];
    }
}
