# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased
## [v0.7.1 - 2022-03-30]
### Fixes
- Processed values from components will be used for running JSON Logic calculations & conditionals now. This fixes problems with empty strings in number fields being used in mathematical calculations, which could potentially cause PHP runtime errors.

## [v0.7.0 - 2022-03-22]
### Added
- Support for JSONLogic has been extended to calculated values.

## [v0.6.0 - 2021-02-22]
### Changes
- Enable support for Laravel 9.

## [v0.5.4 - 2021-02-04]
### Fixes
- File components that use S3 and have a directory set will pass server-side validations now.

## [v0.5.3 - 2022-01-29]
### Fixes
- Numeric values for `Select` & `Radio` components no longer cause the validation to fail.

## [v0.5.2 - 2022-01-20]
### Changes
- Several methods in `Storage\S3Driver` have a new optional parameter for an array of parameters to add to pre-signed URL `getCommand()` calls. These can be utilized to add S3 encryption and use other S3 features.
- The `urlValiditiyPeriod` parameter introduced in v0.5.1 had a spelling error. If you are using PHP 8 named parameters, this may be breaking, if you've started using it since the last release.

## [v0.5.1 - 2022-01-19]
### Changes
- The `Storage\S3Driver::getDirectDownloadLink` method now has new optional parameters.

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
