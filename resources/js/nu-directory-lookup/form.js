import textEditForm from 'formiojs/components/textfield/TextField.form';

export default function(...extend) {
    return textEditForm([
        {
            key: 'display',
            components: [
                {
                    key: 'inputMask',
                    ignore: true,
                },
                {
                    key: 'allowMultipleMasks',
                    ignore: true,
                },
                {
                    key: 'showWordCount',
                    ignore: true
                },
                {
                    key: 'showCharCount',
                    ignore: true
                },
                {
                    key: 'spellcheck',
                    ignore: true
                },
                {
                    key: 'prefix',
                    ignore: true
                },
                {
                    key: 'suffix',
                    ignore: true
                },
                {
                    key: 'autocomplete',
                    ignore: true
                },
                {
                    key: 'widget.type',
                    ignore: true
                },
                {
                    key: 'widget',
                    ignore: true
                },
            ],
        },
        {
            key: 'validation',
            components: [
                {
                    key: 'validate.minLength',
                    ignore: true
                },
                {
                    key: 'validate.maxLength',
                    ignore: true
                },
                {
                    key: 'validate.pattern',
                    ignore: true
                },
                {
                    key: 'validate.minWords',
                    ignore: true
                },
                {
                    key: 'validate.maxWords',
                    ignore: true
                },
                {
                    key: 'validate.customMessage',
                    defaultValue: 'You must enter a valid netID, email address, or student/employee ID.',
                    ignore: false,
                }
            ],
        }
    ], ...extend);
}
