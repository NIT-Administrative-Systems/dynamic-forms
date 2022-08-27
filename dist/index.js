import 'formiojs';
import Defaults from './defaults';
import CustomTemplates from "./custom-templates";
import BuilderSidebar from "./builder-sidebar"

/*
* We explicitly do NOT support JS evaluation stuff, so disable all of it.
* This is a limitation of our backend: some of these scripts (probably; assuming)
* need to run server-side too, to ensure the client isn't doing shenanigans.
*/
FormioUtils.Evaluator.noeval = true;

// -------------------------------------------------------------------------
// If you want to load custom code (like additional components), do it here!
// -------------------------------------------------------------------------

/**
 * Runs global customizations for the Button and File component's editForm that were not possible
 * with the typical way of adjusting editForms.
 */
Defaults.globalButtonCustomization();
Defaults.globalFileCustomization();
Defaults.globalResourceCustomization();

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
