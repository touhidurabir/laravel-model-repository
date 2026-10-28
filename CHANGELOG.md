# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
- Laravel 11, 12 and 13 support added.
- PHPUnit 10, 11 and 12 support for tests; test methods now use `#[Test]` attributes alongside the `@test` annotations.
- Fixed PHP 8.4 implicit nullable parameter deprecations in `BaseRepository::paginate` and `CommandExceptionHandler::outputConsoleException`.
- Enabled the existing `make:repository` "class already exists" test, which was missing its `@test` annotation.
- Fixed `BaseRepository::restore` when given a primary key instead of a model instance (`onlyTrashed` was accessed as a property).
- Code style formatted to PSR-12 with Laravel Pint (`composer format` / `composer lint`).


## [1.0.0] - 2021-08-21
- Initial release

## [1.0.1] - 2021-08-24
- Test Updates

## [1.0.2] - 2021-08-29
- Bug fix

## [1.1.0] - 2021-09-08
- Removed the manual stub generation process
- Added the touhidurabir/laravel-stub-generator package as the stub generator process handler
- Added replaceable feature to repository command
- Bug Fix
- Readme Update


## [1.1.1] - 2021-09-08
- Bug Fix
- Test Update

## [1.1.2] - 2021-10-14
- Bug Fix
- Readme Update

## [1.1.3] - 2021-10-27
- Tests update
- Readme Update
- Issue Template added
