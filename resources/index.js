import 'formiojs';
import Defaults from './defaults';
import NuDirectoryLookup from "../nu-directory-lookup";
import NuDirectoryEditForm from "../nu-directory-lookup/form";
import CustomTemplates from "./custom-templates";

/*
* We explicitly do NOT support JS evaluation stuff, so disable all of it.
* This is a limitation of our backend: some of these scripts (probably; assuming)
* need to run server-side too, to ensure the client isn't doing shenanigans.
*/
FormioUtils.Evaluator.noeval = true;

// Enable custom modules
Formio.use(NuDirectoryLookup);
Formio.Components.components.nuDirectoryLookup.editForm = NuDirectoryEditForm;

/**
 * Runs global customizations for the Button component's editForm that were not possible
 * with the typical way of adjusting editForms.
 */
Defaults.globalButtonCustomization();

/**
 * Apply any custom templates.
 */
_.forOwn(CustomTemplates, (template, name) => Formio.Templates.current[name] = template);


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

    return origFormioBuilder(element, form, options);
};
