# Changelog

All Notable changes to `laravel-validator` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

…

## 0.2.0 (2017-08-02)

### Added

- Added support for multi-dimensional wildcard (*) array rules
- Support for Laravel 5.5 and up

### Removed

- Removed the obsolete service provider
- Support for Laravel 5.3 and below

### Fixed

- Removed the need to have `sebastiaanluca/laravel-helpers` installed (used the `array_expand` method which is not included in this package)
