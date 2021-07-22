# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased

## [v0.3.0] - 2021-07-22
### Added
- Support for uploading files to the server instead of S3 added.
- A new `MIX_STORAGE_DEFAULT_VALUE` environment variable has been added to pre-select & enforce a particular file upload method.

### Changed
- The methods from the `HandlesDynamicFormsStorage` trait have been renamed.
    - The route stub file has been updated accordingly. Please see the upgrading guide in the docs site for resolving this change.
- `BaseComponent` now uses a new `hasMultipleValuesForValidation()` method to determine if a field in single-value mode should be validated in multi-value mode.

### Fixed
- The `ComponentRegistry` now consistently registers components without a leading slash in namespaces. 

## [v0.2.0] - 2021-05-13
### Changed
- The `dynamic-forms:install` artisan command does a better job of installing now:
    - Adds the `formiojs` dependency to `package.json`
    - Adds the Formiojs CSS to your `app.scss` file
    - Adds a `builder-sidebar.js` file w/ sensible builder defaults that remove unsupported components

## [v0.1.0] - 2021-05-04
- Initial release.
