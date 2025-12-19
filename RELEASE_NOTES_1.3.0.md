# Release Notes - Version 1.3.0

## 🚀 What's New

### New User Flights Query Endpoint

This release introduces a new dedicated endpoint for retrieving flights associated with a specific user, along with important bug fixes to the GraphQL query building system.

## ✨ Features

### GetUserFlightsRequest - User-Specific Flight Queries

A new request class that allows querying flights for a specific user with advanced filtering capabilities:

- **User-Specific Queries**: Retrieve all flights associated with a particular user ID
- **Flexible Filtering**: Support for multiple filter options:
  - `from`: Filter flights from a specific date/time
  - `to`: Filter flights until a specific date/time
  - `all`: Include all flights regardless of status
  - `last`: Get the last N flights
  - `first`: Get the first N flights
- **Rich Default Fields**: Includes comprehensive flight information out of the box:
  - Flight times (offBlock, onBlock, takeoff, landing)
  - Aircraft details (callSign, model)
  - Airport information (departure and arrival)
  - Crew information (primary and secondary logs with user details)
- **Customizable Fields**: Override default fields to request exactly the data you need
- **Full Pagination Support**: Integrated with GraphQL pagination system

#### Usage Example

```php
use Tschope\FlightLogger\FlightLoggerConnector;
use Tschope\FlightLogger\Requests\Users\GetUserFlightsRequest;

$connector = new FlightLoggerConnector('your-api-token');

// Get all flights for a user
$request = new GetUserFlightsRequest('user-id-123');
$response = $connector->send($request);

// Get flights with date filtering
$request = new GetUserFlightsRequest('user-id-123', [
    'from' => '2024-01-01T00:00:00Z',
    'to' => '2024-12-31T23:59:59Z',
]);

// Get last 10 flights
$request = new GetUserFlightsRequest('user-id-123', [
    'last' => 10,
]);

// Custom fields
$customFields = [
    'id',
    'offBlock',
    'onBlock',
    'aircraft { callSign }',
];
$request = new GetUserFlightsRequest('user-id-123', [], $customFields);
```

## 🐛 Bug Fixes

### Improved buildFieldsString() Method

Fixed issues with nested GraphQL structure handling in the `buildFieldsString()` method:

- **Complete Structure Detection**: Now properly detects when a field already contains a complete nested structure (both `{` and `}`)
- **Better Formatting**: Fields with complete structures like `contact { email phone }` are now used as-is, preventing malformation
- **Backward Compatibility**: Legacy handling for incomplete structures remains intact
- **Enhanced Documentation**: Improved PHPDoc explaining the method's behavior

#### Technical Details

The bug fix addresses an issue where fields containing complete nested structures were being incorrectly reformatted. The updated logic:

```php
// Now correctly handles complete structures
if (str_contains($field, '}')) {
    $lines[] = $indentation . $field;  // Use as-is
} else {
    // Legacy handling for incomplete structures
    // ...
}
```

This ensures that fields defined as `aircraft { callSign model }` are properly rendered in the GraphQL query without being split or reformatted incorrectly.

## 📊 Benefits for Developers

1. **User-Centric Queries**: Easy access to flight data organized by user
2. **Better Performance**: Targeted queries reduce data transfer and processing
3. **Flexible Filtering**: Powerful date and count-based filtering options
4. **Reliable Query Building**: Bug fix ensures consistent GraphQL query formatting
5. **Consistent API**: New request follows the same patterns as existing endpoints

## 🔄 Migration Guide

### No Breaking Changes

This release maintains **full backward compatibility**. No changes are required to existing code using this package.

### New Functionality

To use the new user flights endpoint:

1. Import the new request class:
```php
use Tschope\FlightLogger\Requests\Users\GetUserFlightsRequest;
```

2. Create and send the request:
```php
$request = new GetUserFlightsRequest($userId, $filters, $fields);
$response = $connector->send($request);
```

3. Access the response data:
```php
$userData = $response->json('data.user');
$flights = $userData['flights']['nodes'];
```

## 📦 Installation

Update your `composer.json`:

```bash
composer require tschope/flightlogger-package:^1.3.0
```

Or update from a previous version:

```bash
composer update tschope/flightlogger-package
```

## 🔧 Technical Details

### Files Changed

- **New**: `src/Requests/Users/GetUserFlightsRequest.php` - Complete user flights query implementation
- **Modified**: `src/Requests/GraphQLRequest.php` - Enhanced `buildFieldsString()` method
- **Updated**: `CHANGELOG.md` - Version 1.3.0 changes documented

### Code Quality

- **Well-Documented**: Comprehensive PHPDoc comments
- **Type-Safe**: Full PHP 8.1+ type declarations
- **Tested**: Follows the same proven patterns as existing request classes
- **Extensible**: Easy to extend for custom use cases

## 🙏 Acknowledgments

This release includes both new features and important bug fixes that improve the reliability and functionality of the FlightLogger PHP package.

---

**Full Changelog**: https://github.com/tschope/flightlogger-package/compare/v1.2.0...v1.3.0
