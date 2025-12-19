# Changelog

All notable changes to `flightlogger-php` will be documented in this file.

## [Unreleased]

## [1.3.0] - 2024-12-19

### Added
- **GetUserFlightsRequest**: New query endpoint to retrieve flights for a specific user
  - Support for user-specific flight filtering (from, to, all, last, first)
  - Customizable flight fields with sensible defaults
  - Includes aircraft, airport, and crew information
  - Full pagination support

### Fixed
- **buildFieldsString()**: Improved handling of nested GraphQL structures
  - Better detection of complete nested field structures (containing both `{` and `}`)
  - Fixed formatting for fields with complex nested objects
  - Maintains backward compatibility with legacy field definitions

## [1.2.0] - 2024-11-15

### Changed
- **Standardized Field String Building**: Moved `buildFieldsString()` method to base `GraphQLRequest` class
- All GraphQL list request classes now use unified field formatting logic
- Enhanced pagination support across all list queries

## [1.1.0] - 2024-11-14

### Added - Laravel Integration ✨
- **Laravel Service Provider**: Auto-discovery support for Laravel
- **Configuration File**: Optional `config/flightlogger.php` for Laravel projects
- **Environment Variable Support**: Token can be loaded from `FLIGHTLOGGER_API_TOKEN`
- **Dependency Injection**: Connector can be injected in Laravel controllers/services
- **`.env.example`**: Example environment configuration file
- **LARAVEL.md**: Complete Laravel integration guide with examples

### Changed
- **FlightLoggerConnector**: Token parameter is now optional
- **Token Resolution**: Automatic token loading with priority system:
  1. Constructor parameter (highest priority)
  2. Laravel config file
  3. Environment variable

### Features
- 🔧 Optional configuration - works with or without explicit token
- 🎯 Laravel auto-discovery - zero configuration setup
- 📝 Comprehensive Laravel documentation
- ⚡ Singleton pattern in Laravel for better performance

## [1.0.0] - 2024-11-14

### Added - Complete API Coverage 🎉

#### Core Infrastructure
- FlightLogger API connector using Saloon with Bearer token authentication
- GraphQL base request class for queries
- GraphQL base mutation class for mutations
- Comprehensive README with usage examples in English
- Example usage file
- Contributing guidelines
- MIT License
- Complete implementation plan document

#### Queries (24 endpoints) - 100% Coverage ✅
**Essential Queries**
- Users: GetUsersRequest, GetUserRequest
- Classes: GetClassesRequest
- Flights: GetFlightsRequest
- Trainings: GetTrainingsRequest
- Aircraft: GetAircraftRequest
- Bookings: GetBookingsRequest
- Programs: GetProgramsRequest, GetUserProgramsRequest
- Operations: GetOperationsRequest
- MyFlightLogger: GetMyFlightLoggerRequest

**Academic Queries**
- ClassTheories: GetClassTheoriesRequest
- ExtraTheories: GetExtraTheoriesRequest
- Exams: GetExamsRequest
- ProgressTests: GetProgressTestsRequest
- TypeQuestionnaires: GetTypeQuestionnairesRequest
- TheoryReleases: GetTheoryReleasesRequest

**Operational Queries**
- DutyTimes: GetDutyTimesRequest
- Rentals: GetRentalsRequest
- Maintenance: GetMaintenancePartsRequest, GetMaintenanceTypesRequest

**Auxiliary Queries**
- Versions: GetVersionsRequest
- Jobs: FetchJobRequest
- Uploads: GetPresignedUploadUrlsRequest

#### Mutations (30 endpoints) - 100% Coverage ✅
**Booking Create Mutations (12)**
- CreateClassTheoryBookingRequest
- CreateExamBookingRequest
- CreateExtraTheoryBookingRequest
- CreateMaintenanceBookingRequest
- CreateMeetingBookingRequest
- CreateMultiStudentBookingRequest
- CreateOperationBookingRequest
- CreateProgressTestBookingRequest
- CreateRentalBookingRequest
- CreateSingleStudentBookingRequest
- CreateTheoryReleaseBookingRequest
- CreateTypeQuestionnaireBookingRequest

**Booking Update Mutations (12)**
- UpdateClassTheoryBookingRequest
- UpdateExamBookingRequest
- UpdateExtraTheoryBookingRequest
- UpdateMaintenanceBookingRequest
- UpdateMeetingBookingRequest
- UpdateMultiStudentBookingRequest
- UpdateOperationBookingRequest
- UpdateProgressTestBookingRequest
- UpdateRentalBookingRequest
- UpdateSingleStudentBookingRequest
- UpdateTheoryReleaseBookingRequest
- UpdateTypeQuestionnaireBookingRequest

**Booking Delete/Cancel Mutations (3)**
- DeleteBookingRequest
- DeleteBookingsRequest
- CancelBookingsRequest

**User Mutations (3)**
- CreateUserRequest
- UpdateUserRequest
- UpdateMyFlightLoggerRequest

### Features
- 🔐 Bearer token authentication
- 🎨 Customizable query fields
- 📄 Pagination support with cursor-based navigation
- 🎯 Default fields for all resources
- 🔧 Full GraphQL query and mutation flexibility
- 🌐 Complete API coverage (54 endpoints)
- 📚 Complete English documentation
- 🏗️ Extensible architecture for community contributions
