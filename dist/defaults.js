import {Formio} from "formiojs";

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
                /**
                 * Ignoring the API tab stops the logic that auto-generates the field key.
                 * This is undesirable -- you end up with a form made of textField1, textField2,
                 * textField3, etc.
                 */
                {
                    key: 'api',
                    ignore: false,
                    components: [
                        {"html":"<p>This is the internal name for the field. You do not need to set this, but it may be useful to know when setting up conditional fields or building reports.</p>","label":"Content","refreshOnChange":false,"key":"content","type":"content","input":false,"tableView":false},
                        {key: 'key', ignore: false, disabled: true},
                        {key: 'tags', ignore: true},
                        {key: 'properties', ignore: true},
                    ],
                },
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
                        {key: 'calculateValuePanel', ignore: false},
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
                    { key: 'editor', defaultValue: 'quill', disabled: true }, // do not set hidden, it won't change if you do that
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
                    { key: 'idPath', ignore: true },
                    // { key: 'template', ignore: true }, // needs to be enabled for resources to work
                    { key: 'refreshOn', ignore: true },
                    { key: 'refreshOnBlur', ignore: true },
                    { key: 'clearOnRefresh', ignore: true },
                    { key: 'customOptions', ignore: true },
                    { key: 'readOnlyValue', ignore: true },
                    { key: 'useExactSearch', ignore: true },
                    { key: 'sort', ignore: true },
                    { key: 'ignoreCache', ignore: true },
                    { key: 'selectThreshold', ignore: true },
                    { key: 'filter', ignore: true },
                    { key: 'addResource', ignore: true },
                    { key: 'reference', ignore: true },
                    { key: 'selectFields', ignore: true },
                ],
            },
            {
                key: 'display',
                ignore: false,
                components: [
                    { key: 'widget', ignore: true }, //The html5 one doesn't support search and choicesjs also looks better
                ],
            },
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
        file: [
            {
                key: 'file',
                ignore: false,
                components: [
                    { key: 'url', defaultValue: '/dynamic-forms/storage/url', disabled: true },
                    { key: 'fileKey', ignore: true },
                    { key: 'privateDownload', ignore: true },
                    { key: 'options', ignore: true },
                    { key: 'dir', ignore: true },
                    { key: 'fileNameTemplate', ignore: true },
                    { key: 'uploadOnly', ignore: true },
                    { key: 'fileTypes', ignore: true },
                    { key: 'image', ignore: true },
                    { key: 'webcam', ignore: true },

                ],
            }
        ],
    },

    /**
     * Builder dropdown values cannot be modified by overriding defaults.
     *
     * This modifies the Button editForm directly & globally, which seems to be
     * the only approach that works.
     *
     * It also modifies the behaviour of the 'saveState' additional field, state,
     * which was not possible from the overrides either.
     */
    globalButtonCustomization: () => {
        var editForm = Formio.Components.components.button.editForm();

        Formio.Utils.getComponent(editForm.components, 'action').data.values = [
            {label: 'Submit', value: 'submit'},
            {label: 'Save Draft', value: 'saveState'},
        ];

        var stateField = Formio.Utils.getComponent(editForm.components, 'state');
        stateField.defaultValue = 'draft'
        stateField.type = 'hidden';

        Formio.Components.components.button.editForm = function() { return editForm; };
    },

    /**
     * Builder dropdown values cannot be modified by overriding defaults.
     *
     * This modifies the File editForm directly & globally, which seems to be
     * the only approach that works.
     *
     */
    globalFileCustomization: () => {
        var editForm = Formio.Components.components.file.editForm();
        var StorageValues = [
            {label: "S3", value: "s3"},
            {label: "Local", value: "url"}
        ];
        if(process.env.MIX_STORAGE_DEFAULT_VALUE === 's3')
        {
            StorageValues = [
                {label: "S3", value: "s3"},
            ];
        }
        if(process.env.MIX_STORAGE_DEFAULT_VALUE === 'url')
        {
            StorageValues = [
                {label: "Local", value: "url"}
            ];
        }

        Formio.Utils.getComponent(editForm.components, 'storage').data.values = StorageValues;

        Formio.Utils.getComponent(editForm.components, 'storage').dataSrc = 'values';

        Formio.Components.components.file.editForm = function() { return editForm; };

    },

    /**
     * Builder defaults to form.io url for resources this changes that.
     * Also restricts the dropdown for Select data source.
     */
    globalResourceCustomization: () => {
        //project URL has to be set or it will redirect to https://form.io
        //"add resource" button needs the base url to be set
        Formio.setProjectUrl(process.env.MIX_APP_URL + '/dynamic-forms');
        Formio.setBaseUrl(process.env.MIX_APP_URL + '/dynamic-forms');

        //Limit options to only Resource and Values
        var editForm = Formio.Components.components.select.editForm();

        Formio.Utils.getComponent(editForm.components, 'select').data.values = [
            { label: 'Values', value: 'values' },
            //{ label: 'URL', value: 'url' },
            { label: 'Resource', value: 'resource' },
            //{ label: 'Custom', value: 'custom' },
            //{ label: 'Raw JSON', value: 'json' },
            //{ label: 'IndexedDB', value: 'indexeddb' },
        ];


        Formio.Components.components.select.editForm = function() { return editForm; };
    }

}
