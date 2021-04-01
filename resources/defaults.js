export default {
    /**
     * Merges user-supplied editForm options into the system default editOptions.
     *
     * This is a difficult problem, because the data looks like this:
     *  {
     *      textarea: [
     *          {
     *              key: 'display',
     *              ignore: false,
     *              components: [
     *                  { key: 'foo', ignore: false },
     *              ],
     *          }
     *      ],
     *  }
     *
     * Doing a recursive merge will fall flat on its face when you hit either array, since a general-purpose
     * recursive or deep merge will merge array key 0 together with the other array key 0, regardless of the key.
     *
     * This algorithm is key-aware and merges properly (ish).
     *
     * @param object Default options for editForm
     * @param optionsToMerge User-specified options.editForm
     * @returns {*}
     */
    configMerge: function (object, optionsToMerge) {
        let path = '';
        // First level is key:value, unwrap it
        _.each(optionsToMerge, function (component, componentName) {
            // Now we're inside the tree
            _.each(component, function (prop, idx) {
                let mergeInto = _.findIndex(_.get(object, componentName), function (comp) {
                    return comp.key === prop.key;
                });

                if (mergeInto > -1) {
                    path = componentName + '.' + mergeInto;

                    _.set(object, path, _.mergeWith(_.get(object, path), prop, function (objValue, srcValue, key) {
                        // @TODO this is going to have the same problem: the components have keys -- make this recursive?
                        // Although I haven't run into a situation like this yet...so might be OK?
                        return key === 'components' ? _.concat(objValue, prop.components) : undefined;
                    }));
                } else {
                    object[componentName].push(prop);
                }
            });
        });

        return object;
    },

    /**
     * Global defaults applied to all field types
     */
    global: function () {
        return _.mapValues(Formio.Components.components, function () {
            return [
                {key: 'api', ignore: true},
                {key: 'logic', ignore: true},
                {key: 'layout', ignore: true},
                {
                    key: 'display',
                    ignore: false,
                    components: [
                        {key: 'customClass', ignore: true},
                        {key: 'hidden', ignore: true},
                        {key: 'mask', ignore: true}, // this is the 'Hide Input' checkbox, NOT the input mask field
                        {key: 'tableView', ignore: true}, // specific to how you view this in the Form.io server admin UI
                        {key: 'modalEdit', ignore: true}, // specific to how you view this in the Form.io server admin UI (I think)
                    ],
                },
                {
                    key: 'data',
                    ignore: false,
                    components: [
                        {key: 'dataType', ignore: true},
                        {key: 'persistent', ignore: true},
                        {key: 'protected', ignore: true},
                        {key: 'dbIndex', ignore: true},
                        {key: 'encrypted', ignore: true},
                        {key: 'redrawOn', ignore: true},
                        {key: 'customDefaultValuePanel', ignore: true},
                        {key: 'calculateValuePanel', ignore: true},
                        {key: 'calculateServer', ignore: true},
                        {key: 'allowCalculateOverride', ignore: true},
                        {key: 'inputFormat', ignore: true},
                    ],
                },
                {
                    key: 'validation',
                    ignore: false,
                    components: [
                        {key: 'unique', ignore: true},
                        {key: 'custom-validation-js', ignore: true},
                        {key: 'json-validation-json', ignore: true},
                    ]
                },
                {
                    key: 'conditional',
                    ignore: false,
                    components: [
                        {key: 'customConditionalPanel', ignore: true},
                    ],
                }
            ];
        })
    },

    /**
     * Defaults for specific field types
     */
    specificFields: {
        textarea: [
            {
                key: 'display',
                ignore: false,
                components: [
                    { key: 'editor', defaultValue: 'ckeditor', disabled: true }, // do not set hidden, it won't change to ckeditor if you do that
                    { key: 'wysiwyg', ignore: true },
                ],
            },
            {
                key: 'data',
                ignore: false,
                components: [
                    { key: 'inputFormat', defaultValue: 'html', disabled: true },
                ],
            }
        ],
        email: [
            {
                key: 'data',
                ignore: false,
                components: [
                    { key: 'inputFormat', ignore: true },
                ],
            },
            {
                key: 'validation',
                ignore: false,
                components: [
                    { key: 'kickbox', ignore: true },
                ],
            },
        ],
        address: [
            {
                key: 'provider',
                ignore: false,
                components: [
                    { key: 'provider', defaultValue: 'nominatim', disabled: true },
                    { key: 'manualModeViewString', ignore: true},
                ],
            }
        ],
        datetime: [
            {
                key: 'date',
                ignore: false,
                components: [
                    { key: 'datePicker.disable', ignore: true }, // feature is broken, so it has been disabled
                ],
            },
            {
                key: 'data',
                ignore: false,
                components: [
                    { key: 'customOptions', ignore: true },
                    { key: 'defaultDate', ignore: true }, // depends on JS eval. it can work (this JS is client-side-only), but it seems dangerous.
                ]
            }
        ],
        select: [
            {
                key: 'data',
                ignore: false,
                components: [
                    { key: 'dataSrc', defaultValue: 'values', disabled: true },
                    { key: 'idPath', ignore: true },
                    { key: 'template', ignore: true },
                    { key: 'refreshOn', ignore: true },
                    { key: 'refreshOnBlur', ignore: true },
                    { key: 'clearOnRefresh', ignore: true },
                    { key: 'customOptions', ignore: true },
                ],
            }
        ],
        time: [
            {
                key: 'data',
                ignore: false,
                components: [
                    { key: 'dataFormat', ignore: true },
                ],
            }
        ],
    },
}