# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/).

## Unreleased
### Fixes
- `SimpleConditional::invoke()` would always return false for multi-value `Select` or `SelectBoxes` components. This has been corrected.

### Changed
- The minimum versions for dependencies have changed. The new requirements are:
    - PHP 8.2
    - Laravel 11
    - Bootstrap 5
    - FontAwesome 6

- The JS file `default.js` references the `.env` value `VITE_STORAGE_DEFAULT_VALUE` instead of `MIX_STORAGE_DEFAULT_VALUE`. If you are using Laravel Mix or another build system, you may need to adjust this code.

- The routes file now removes the `\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken` middleware from the POSTs that formio makes, as `App\Http\Middleware\VerifyCsrfToken` is no longer part of the Laravel skeleton. If you are upgrading from Laravel 10 and have not removed your stock middleware, this does not need to be updated.

## [v0.15.1 - 2024-03-11]
### Fixes
- `BaseComponent::submissionValue()`, which provides the method for most components, will handle multiple-value components more elegantly and should never return a `null` for them, even if this is what was represented in the submission JSON.

## [v0.15.0 - 2024-03-05]
### Breaking Change
- The `ResourceInterface::submissions` method signature now includes an optional `$context` parameter. To migrate, ensure that all custom resources classes incorporate the new parameter.

### Added
- Support for non-scalar option values in the `Select` component.

## [v0.14.1 - 2024-02-29]
### Fixes
- Fixes the check to ensure Quill is the default Textarea editor.

## [v0.14.0 - 2024-02-28]
### Added
- Added `additional()` getter to the `ComponentInterface` for getting other settings that are not used by the dynamic-forms package directly.

## [v0.13.0 - 2023-12-06]
### Added
- Added getters to the `ComponentInterface` for validation rules.

## [v0.12.2 - 2023-05-22]
### Fixed
- This fixes a bug introduced in v0.12.1; `show:false` in a `SimpleConditional` was being ignored instead of evaluated correctly. 
- 
## [v0.12.1 - 2023-05-02]
### Fixed
- An incomplete `SimpleConditional` will be ignored instead of erroring.


## [v0.12.0 - 2023-04-06]
### Internal
- Updated dependencies for Laravel compatibility 10. There are no breaking changes.

## [v0.11.4 - 2023-03-27]
### Internal
- Works around [an issue concerning the translation of errors](https://github.com/laravel/framework/pull/46378) from `ValidatedForm` in later versions of Laravel 9.x and 10.

## [v0.11.2 - 2023-03-22]
### Internal
- Swapped to a fork of the lodash-php package that has PHP 8.2 compatibility fixes.

## [v0.11.1 - 2023-01-18]
### Fixed
- The `Select` component's `initSrcValues()` method has been updated by setting an empty array for dropdowns that lack options instead of `null`.

## [v0.11.0 - 2023-01-09]
### Added
- The `Button` component has a new method, `action()` which exposes the button's action type.

### Infra
- PHP 8.2 has been added to the test matrix.

## [v0.10.0 - 2022-11-08]
### Added
- The `Select` component has a new method, `options()`, which exposes the labels for options.

### Fixed
- Problems with the installer were corrected. Thank you [bilogic](https://github.com/bilogic)!

## [v0.9.2 - 2022-09-19]
### Added
- The `Survey` component has two new methods, `questionsWithLabels()` and `choicesWithLabels()`. These are used to get the key/label lookups for both the questions and answers, which were not previously available.

## [v0.9.1 - 2022-09-08]
### Changed
- Fixing how the resource registry stores its list of resources.
## [v0.9.0 - 2022-08-26]
### Added
- Support for Resources within the Select component.

## [v0.8.4 - 2022-07-26]
### Fixes
- Allows for regular textarea editors in addition to Quill.

## [v0.8.3 - 2022-07-06]
### Fixes
- Before comparing values for select, selectboxes, radio, and survey components, the values will be trimed. This matches the behaviour of the default Laravel middleware, which trims all submitted form values.

## [v0.8.2 - 2022-07-01]
### Fixes
- Fixes `Survey` components failing to validate if a question's `value` included a dot (.) character.

## [v0.8.1 - 2022-05-11]
### Fixes
- The `ValidatedForm::allFiles()` method would not return files if the key contained a folder prefix. This has been corrected.

## [v0.8.0 - 2022-05-09]
### Added
- Adds support for Hidden components.

## [v0.7.7 - 2022-04-30]
### Fixes
- An edge case where select & radio components in multiple-value mode when provided with a `null` submission value would trigger a runtime error has been corrected.

## [v0.7.6 - 2022-04-26]
### Fixes
- If a component with child components is excluded by conditional logic, its children are now excluded from validation as well. This mirrors the formio.js behaviour.
- When a file component was excluded by conditional logic, validating the form would cause a PHP error. This has been corrected.

## [v0.7.5 - 2022-04-25]
### Added
- Added `ComponentInterface::defaultValue()`, for accessing the configured default value for a component. 

## [v0.7.4 - 2022-04-22]
### Fixes
- When running AJAX requests back to Laravel via a form component, server-side errors could cause the user to be redirected to the AJAX endpoint URL instead of the form. This has been corrected. 

## [v0.7.3 - 2022-04-10]
- `File` components have a new `getStorageDirectory()` method to access the configured storage directory. 

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
