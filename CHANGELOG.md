# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased

## [v0.5.1 - 2022-01-19]
### Fixes
- Fixes a bug in `Rules\FileExists` when using the S3 storage driver that caused validation to fail if the original filename contains URL-encodable characters.

## [v0.5.0 - 2021-11-03]
### Changes
- Set the default TextArea editor to Quill, and removed support for CKEDitor.

## [v0.4.0 - 2021-07-29]
### Added
- Support for JSONLogic (augmented with lodash) for conditional form fields.

### Fixes
- Fixed a bug in `ValidatedForm::allFiles()` when components were nested.

## [v0.3.1] - 2021-07-27
## Added
- Added an `allFiles()` method to `ValidatedForm`s to easily access submitted files.

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
