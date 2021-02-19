# Form.io Backend for Laravel
This is broken out into its own folder & namespace because it'll be split out from this app's repo and released as a stand-alone open source package once it's ready.

Any Competitive Applications-specific code should live in `app/`, e.g. the code for the netID lookup form type does not belong here.

## Purpose
The folks at [form.io](https://form.io) have made a wonderful UI library, formiojs, that presents a powerful & user-friendly form builder, as well as rendering the forms it generates.

It is intended for use with their platform -- or at least the open-source backend server -- but that requires running a nodejs process and giving it its own database.

This library reimplements enough of the form.io backend to validate and persist forms from your Laravel application.
