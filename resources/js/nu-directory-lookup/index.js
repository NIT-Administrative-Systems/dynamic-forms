import DirectoryLookup from "./directory-lookup";
import template from "./template"

export default {
    components: {
        nuDirectoryLookup: DirectoryLookup,
    },
    templates: {
        bootstrap: {
            nuDirectoryLookup: template
        },
    },
}
