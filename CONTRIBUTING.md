# Contributing to FlightLogger PHP SDK

Thank you for considering contributing to this project! This document provides guidelines for contributions.

## How to Contribute

### Reporting Bugs

If you've found a bug, please open an issue on GitHub including:

- A clear description of the problem
- Steps to reproduce the bug
- Expected behavior vs. actual behavior
- PHP version you're using
- Any other relevant information

### Suggesting Improvements

Improvement suggestions are always welcome! Please:

- Check if a similar issue doesn't already exist
- Clearly explain the benefit of the improvement
- Provide usage examples when possible

### Pull Requests

1. Fork the repository
2. Create a branch for your feature (`git checkout -b feature/MyFeature`)
3. Commit your changes (`git commit -m 'Add: my new feature'`)
4. Push to the branch (`git push origin feature/MyFeature`)
5. Open a Pull Request

## Adding New Endpoints

The FlightLogger API has many endpoints. If you want to add support for a new endpoint:

1. Create a new directory in `src/Requests/` if necessary
2. Create a new class that extends `GraphQLRequest` or `GraphQLMutation`
3. Implement the required methods:
   - `getQuery()` or `getMutation()`: Returns the GraphQL query/mutation
   - `getVariables()`: Returns the query/mutation variables
   - `getDefaultFields()`: Defines the default fields returned

### Example Structure

```php
<?php

declare(strict_types=1);

namespace Tschope\FlightLogger\Requests\NewResource;

use Tschope\FlightLogger\Requests\GraphQLRequest;

class GetNewResourceRequest extends GraphQLRequest
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
        $fieldsString = implode("\n            ", $this->fields);

        return <<<GQL
        query NewResource(
          \$parameter: Type
        ) {
          newResource(
            parameter: \$parameter
          ) {
            edges {
              node {
                {$fieldsString}
              }
            }
            pageInfo {
              hasNextPage
              hasPreviousPage
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
            'field1',
            'field2',
        ];
    }
}
```

## Code Standards

- Use PHP 8.1+ features when appropriate
- Follow PSR-12 for code style
- Use type hints and return types
- Add DocBlocks to public classes and methods
- Keep code in English (variable names, methods, etc.)
- Documentation and comments can be in English

### Running PHP CS Fixer (when available)

```bash
composer pint
```

## Tests

When adding new features, consider adding tests:

```bash
composer test
```

## Documentation

- Update README.md if necessary
- Add usage examples in the `examples/` directory
- Keep documentation clear and in English

## Code of Conduct

- Be respectful to other contributors
- Keep discussions focused and constructive
- Accept feedback positively

## Questions?

If you have questions about how to contribute, feel free to open an issue with the "question" tag.

## License

By contributing to this project, you agree that your contributions will be licensed under the MIT license.
