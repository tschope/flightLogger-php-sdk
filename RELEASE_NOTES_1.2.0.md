# Release Notes - Version 1.2.0

## 🚀 What's New

### Improved GraphQL Query Building

This release introduces significant improvements to how GraphQL queries are constructed, making the package more maintainable and consistent across all endpoints.

## ✨ Features

### Standardized Field String Building

- **Unified Field Formatting**: Moved `buildFieldsString()` method to the base `GraphQLRequest` class, ensuring all query requests use the same field formatting logic
- **Better Nested Structure Support**: Improved handling of nested field structures (e.g., `contact { email phone }`) across all GraphQL queries
- **Consistent API**: All 20+ Get*Request classes now use the same field building mechanism

### Enhanced Pagination Support

All list queries now consistently support:
- Cursor-based pagination with `first` and `after` parameters
- `endCursor` in `pageInfo` for seamless page navigation
- `hasNextPage` and `hasPreviousPage` indicators
- `startCursor` for backward pagination

## 🔧 Technical Improvements

### Affected Components

All GraphQL list request classes have been updated:
- `GetUsersRequest`
- `GetClassesRequest`
- `GetFlightsRequest`
- `GetBookingsRequest`
- `GetTrainingsRequest`
- `GetAircraftRequest`
- `GetProgramsRequest`
- `GetOperationsRequest`
- `GetUserProgramsRequest`
- `GetClassTheoriesRequest`
- `GetExtraTheoriesRequest`
- `GetExamsRequest`
- `GetProgressTestsRequest`
- `GetTypeQuestionnairesRequest`
- `GetTheoryReleasesRequest`
- `GetDutyTimesRequest`
- `GetRentalsRequest`
- `GetMaintenancePartsRequest`
- `GetMaintenanceTypesRequest`
- `GetVersionsRequest`

### Code Quality

- **DRY Principle**: Eliminated duplicate field formatting logic across 20+ request classes
- **Maintainability**: Future changes to field building logic only need to be made in one place
- **Documentation**: Added comprehensive PHPDoc for the `buildFieldsString()` method

## 📊 Benefits for Developers

1. **Consistent Behavior**: All queries now format fields identically
2. **Easier Customization**: Override `buildFieldsString()` in the base class to affect all queries
3. **Better IntelliSense**: Shared method provides better IDE support and autocomplete
4. **Reduced Bugs**: Single implementation reduces the chance of inconsistencies

## 🔄 Migration Guide

### No Breaking Changes

This release maintains **full backward compatibility**. No changes are required to existing code using this package.

### Optional Improvements

If you're extending any of the Get*Request classes and overriding field formatting, consider using the new `buildFieldsString()` method for consistency:

```php
// Before (still works, but not recommended)
$fieldsString = implode("\n", $this->fields);

// After (recommended)
$fieldsString = $this->buildFieldsString($this->fields);
```

## 📦 Installation

Update your `composer.json`:

```bash
composer require tschope/flightlogger-package:^1.2.0
```

Or update from a previous version:

```bash
composer update tschope/flightlogger-package
```

## 🙏 Acknowledgments

Special thanks to all contributors who helped identify the need for this refactoring and provided valuable feedback on the implementation.

---

**Full Changelog**: https://github.com/tschope/flightlogger-package/compare/v1.1.0...v1.2.0
