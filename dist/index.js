import {Formio} from 'formiojs';
import * as FormioUtils from 'formiojs/utils/utils';
import _ from 'lodash-es';
import Defaults from './defaults';
import CustomTemplates from "./custom-templates";
import BuilderSidebar from "./builder-sidebar"
import S3 from "./providers/s3"
import CustomSurvey from "./components/CustomSurvey";

/*
* We explicitly do NOT support JS evaluation stuff, so disable all of it.
* This is a limitation of our backend: some of these scripts (probably; assuming)
* need to run server-side too, to ensure the client isn't doing shenanigans.
*/
FormioUtils.Evaluator.noeval = true;

// -------------------------------------------------------------------------
// If you want to load custom code (like additional components), do it here!
// -------------------------------------------------------------------------

Formio.use({
    components: {
        survey: CustomSurvey
    }
});

/**
 * Runs global customizations for the Button and File component's editForm that were not possible
 * with the typical way of adjusting editForms.
 */
Defaults.globalButtonCustomization();
Defaults.globalFileCustomization();
Defaults.globalResourceCustomization();
Defaults.globalTextareaCustomizations();

/**
 * The S3 PutObject needs to include the headers that the server advises it to include.
 * There was no way to do this with the stock implementation, so we've ejected it and hacked it up.
 */
Formio.Providers.addProvider('storage', 's3', S3);

const port = window.location.port ? `:${window.location.port}`: '';
const formioUrl = `${window.location.protocol}//${window.location.hostname}${port}/dynamic-forms`;
Formio.setProjectUrl(formioUrl);
Formio.setBaseUrl(formioUrl);

/**
 * Apply any custom templates.
 */
_.forOwn(CustomTemplates, (template, name) => Formio.Templates.current[name] = template);

/**
 * Make the default builder sidebar config available for use in script tags.
 */
window.DynamicFormsBuilderSidebar = BuilderSidebar;

/**
 * Disable editForm (the modal that pops up when you add/edit a field in the Builder) options that
 * we do not want to support.
 *
 * There are two reasons we do not want to support an option:
 *
 *    1. It's too technical for the target audience (don't present choices they don't care about)
 *    2. It is not supported in the backend (yet, or ever)
 *
 * This is achieved by decorating the Formio.builder method with one that sets the editForm options.
 * To developers working in Blade, this will seem to be happening magically, and will not inhibit their
 * ability to work with the Formio.builder() method per Form.io's docs.
 */
const origFormioBuilder = Formio.builder;
Formio.builder = function (element, form, options) {
    options = options || { editForm: {} };

    options.editForm = Defaults.configMerge(
        Defaults.configMerge(Defaults.global(), Defaults.specificFields),
        options.editForm
    );

    if (! options.builder) {
        options.builder = BuilderSidebar;
    }

    if (options.requiredComponents) {
        options.builder.custom.components = options.requiredComponents;
    } else {
        delete options.builder.custom;
    }

    return origFormioBuilder(element, form, options);
};

/**
 * Hijack the creation of forms so we can inject our fileService w/ the URLs set correctly.
 */
const origFormioCreateForm = Formio.createForm;
Formio.createForm = function (element, form, options) {
    options = options || {};

    const fileService = new Formio();
    fileService.formUrl = '/dynamic-forms';
    options.fileService = fileService;

    return origFormioCreateForm(element, form, options);
}

window.Formio = Formio;
