import _ from 'lodash';
import { Formio } from 'formiojs';
import NestedComponent from 'formiojs/components/_classes/nested/NestedComponent';
import ContainerComponent from 'formiojs/components/container/Container';
import Field from 'formiojs/components/_classes/field/Field';

const RemoveValueIconHiddenClass = 'directory-search-remove-value-icon--hidden';
const ChildConditional = 'show = _.get(instance, \'parent.manualMode\', false);';

export const DirectoryLookupMode = {
  DirectorySearch: 'directory-search',
  Manual: 'manual',
};

export default class DirectoryLookup extends ContainerComponent {
    static schema(...extend) {
        return ContainerComponent.schema({
            type: 'nuDirectoryLookup',
            label: 'NU Directory Lookup',
            key: 'nuDirectory',
            hideLabel: false,
            manualMode: false,
            components: [
                {
                    label: 'NetID',
                    key: 'netid',
                    type: 'textfield',
                    input: true,
                    customConditional: ChildConditional,
                },
                {
                    label: 'Name',
                    key: 'name',
                    type: 'textfield',
                    input: true,
                    customConditional: ChildConditional,
                },
                {
                    label: 'Email',
                    key: 'email',
                    type: 'email',
                    input: true,
                    customConditional: ChildConditional,
                },
                {
                    label: 'Title',
                    key: 'textfield',
                    type: 'email',
                    input: true,
                    customConditional: ChildConditional,
                },
            ],
        }, ...extend);
    }

    mergeSchema(component = {}) {
        let { defaultSchema } = this;

        if (component.components) {
            defaultSchema = _.omit(defaultSchema, 'components');
        }

        return _.defaultsDeep(component , defaultSchema);
    }

    init() {
        this.components = this.components || [];
        if (this.builderMode || this.manualModeEnabled) {
            NestedComponent.prototype.addComponents.call(this, this.manualMode ? this.person : {});
        }

        Field.prototype.init.call(this);
    }

    static get builderInfo() {
        return {
            title: 'NU Directory',
            group: 'advanced',
            icon: 'graduation-cap',
            documentation: '',
            weight: 20,
            schema: DirectoryLookup.schema(),
        };
    }

    get emptyValue() {
        return this.manualModeEnabled
            ? {
                mode: DirectoryLookupMode.DirectorySearch,
                person: { display: '' },
            }
            : {};
    }

    get mode() {
        if (!this.manualModeEnabled) {
            return DirectoryLookupMode.DirectorySearch;
        }

        return this.dataValue?.mode ?? DirectoryLookupMode.DirectorySearch;
    }

    set mode(value) {
        if (this.manualModeEnabled) {
            this.dataValue.mode = value;
        }
    }

    get directorySearchMode() {
        return this.mode === DirectoryLookupMode.DirectorySearch;
    }

    get manualMode() {
        return this.mode === DirectoryLookupMode.Manual;
    }

    get manualModeEnabled() {
        return !this.isMultiple && Boolean(this.component.enableManualMode);
    }

    restoreComponentsContext() {
        this.getComponents().forEach((component) => {
            component.data = this.person;
            component.setValue(component.dataValue, {
                noUpdateEvent: true,
            });
        });
    }

    get isMultiple() {
        return Boolean(this.component.multiple);
    }

    get person() {
        if (this.isMultiple) {
            return _.isArray(this.dataValue) ? this.dataValue : [this.dataValue];
        }
        // Manual mode is not implementing for multiple value
        return (this.manualModeEnabled && this.dataValue) ? this.dataValue.person : this.dataValue;
    }

    set person(value) {
        if (this.manualModeEnabled && !this.isMultiple) {
            this.dataValue.person = value;
        }
        else {
            this.dataValue = value;
        }
    }

    get defaultValue() {
        let defaultValue = super.defaultValue;

        if (this.isMultiple) {
            defaultValue = _.isArray(defaultValue) ? defaultValue : [defaultValue];
        }

        return defaultValue;
    }

    get defaultSchema() {
        return DirectoryLookup.schema();
    }

    normalizeValue(value) {
        return this.manualModeEnabled
            ? {
                mode: DirectoryLookupMode.DirectorySearch,
                person: value,
            }
            : value;
    }

    setValue(value, flags = {}) {
        const changed = Field.prototype.setValue.call(this, value, flags);

        if (this.manualMode) {
            this.restoreComponentsContext();
        }

        if (changed || !_.isEmpty(value) && flags.fromSubmission) {
            this.redraw();
        }

        return changed;
    }

    static get modeSwitcherRef() {
        return 'modeSwitcher';
    }

    static get removeValueIconRef() {
        return 'removeValueIcon';
    }

    static get searchInputRef() {
        return 'searchInput';
    }

    static get indicatorRef() {
        return 'indicator';
    }

    static get addRowButtonRef() {
        return 'addButton';
    }

    static get removeRowButtonRef() {
        return 'removeRow';
    }

    get modeSwitcher() {
        return this.refs
            ? (this.refs[DirectoryLookup.modeSwitcherRef] || null)
            : null;
    }

    get removeValueIcon() {
        return this.refs
            ? (this.refs[DirectoryLookup.removeValueIconRef] || null)
            : null;
    }

    get searchInput() {
        return this.refs
            ? (this.refs[DirectoryLookup.searchInputRef] || null)
            : null;
    }

    get indicator() {
        return this.refs
            ? (this.refs[DirectoryLookup.indicatorRef] || null)
            : null;
    }

    get addRowButton() {
        return this.refs
            ? (this.refs[DirectoryLookup.addRowButtonRef] || null)
            : null;
    }

    get removeRowButton() {
        return this.refs
            ? (this.refs[DirectoryLookup.removeRowButtonRef] || null)
            : null;
    }

    get searchInputAttributes() {
        const attr = {
            name: this.options.name,
            type: 'text',
            class: 'form-control',
            lang: this.options.language,
            tabindex: this.component.tabindex || 0,
        };

        if (this.component.placeholder) {
            attr.placeholder = this.t(this.component.placeholder), { _userInput: true };
        }

        if (this.disabled) {
            attr.disabled = 'disabled';
        }

        _.defaults(attr, this.component.attributes);

        return attr;
    }

    get templateName() {
        return 'nuDirectoryLookup';
    }

    get gridTemplateName() {
        return 'multiValueTable';
    }

    get rowTemplateName() {
        return 'multiValueRow';
    }

    get hasChildren() {
        return !this.isMultiple && (this.builderMode || this.manualModeEnabled);
    }

    get addAnother() {
        return this.t(this.component.addAnother || 'Add Another');
    }

    renderElement(value) {
        return this.renderTemplate(this.templateName, {
            children: this.hasChildren ? this.renderComponents() : '',
            nestedKey: this.nestedKey,
            inputAttributes: this.searchInputAttributes,
            ref: {
                modeSwitcher: DirectoryLookup.modeSwitcherRef,
                removeValueIcon: DirectoryLookup.removeValueIconRef,
                searchInput: DirectoryLookup.searchInputRef,
                indicator: DirectoryLookup.indicatorRef,
            },
            rawValue: value,
            displayValue: this.getDisplayValue(value),
            mode: {
                directorySearch: this.directorySearchMode,
                manual: this.manualMode,
            },
        });
    }

    renderRow(value, index) {
        return this.renderTemplate(this.rowTemplateName, {
            index,
            disabled: this.disabled,
            element: `${this.renderElement(value, index)}`,
        });
    }

    renderGrid() {
        return this.renderTemplate(this.gridTemplateName, {
            rows: this.person.map(this.renderRow.bind(this)).join(''),
            disabled: this.disabled,
            addAnother: this.addAnother,
        });
    }

    render() {
        if (this.isMultiple) {
            return super.render(this.renderGrid());
        }

        return super.render(this.renderElement(this.person));
    }

    onDirectorySearchResult(person, element, indicator, index) {
        // Check if this is still the "current" request & discard if there's a newer fetch in-flight
        if (element.value != person.display) {
            this.updateRemoveIcon(index);
            return;
        }

        if (this.isMultiple) {
            this.person[index] = person;
            this.person = [...this.person];
        } else {
            this.person = person;
        }

        this.triggerChange({
            modified: true,
        });

        if (element) {
            element.value = this.getDisplayValue(this.isMultiple ? this.person[index] : this.person);
        }

        if (indicator) {
            indicator.innerHTML = DirectoryLookup.indicatorResultTemplate(person);
        }

        this.updateRemoveIcon(index);
    }

    addRow() {
        this.person = this.person.concat(this.emptyValue);
        super.redraw();
    }

    removeValue(index) {
        console.log('SOUP MESSAGE');
        this.person.splice(index, 1);
        this.redraw();
        this.triggerRootChange();
    }

    attach(element) {
        const result = ((this.builderMode || this.manualMode) ? super.attach : Field.prototype.attach).call(this, element);

        const debounceWaitMs = 300;
        this.loadRefs(element, {
            [DirectoryLookup.addRowButtonRef]: 'single',
            [DirectoryLookup.modeSwitcherRef]: 'single',
            [DirectoryLookup.removeRowButtonRef]: 'multiple',
            [DirectoryLookup.removeValueIconRef]: 'multiple',
            [DirectoryLookup.searchInputRef]: 'multiple',
            [DirectoryLookup.indicatorRef]: 'multiple',
        });

        this.searchInput.forEach((element, index) => {
            if (!this.builderMode && element) {
                this.addEventListener(element, 'keydown', _.debounce(() => {
                    let indicator = this.indicator[index];
                    if (element.value == '') {
                        this.clearPerson(element, index);
                        indicator.innerHTML = DirectoryLookup.indicatorDefaultTemplate();

                        return;
                    }

                    this.clearPerson(element, index, element.value);
                    indicator.innerHTML = DirectoryLookup.indicatorAjaxTemplate();

                    Formio.request(
                        '/dynamic-forms/directory/' + element.value,
                        'get',
                        null,
                        new Headers({'Accept': 'application/json'}),
                        { withCredentials: true }
                    ).then((resp) => this.onDirectorySearchResult(resp, element, indicator, index))
                    .catch((resp) => this.onDirectorySearchResult(resp, element, indicator, index));
                }, debounceWaitMs));
            }
        });

        if (this.addRowButton) {
            this.addEventListener(this.addRowButton, 'click', event => {
                event.preventDefault();
                this.addRow();
            });
        }

        this.removeRowButton.forEach((removeRowButton, index) => {
            this.addEventListener(removeRowButton, 'click', event => {
                event.preventDefault();
                this.removeValue(index);
            });
        });

        if (this.modeSwitcher) {
            this.addEventListener(this.modeSwitcher, 'change', () => {
                if (!this.modeSwitcher) {
                    return;
                }

                this.dataValue = this.emptyValue;
                this.mode = this.modeSwitcher.checked
                    ? DirectoryLookupMode.Manual
                    : DirectoryLookupMode.DirectorySearch

                if (!this.builderMode) {
                    if (this.manualMode) {
                        this.restoreComponentsContext();
                    }

                    this.triggerChange({
                        modified: true,
                    });
                }

                this.redraw();
            });
        }

        if (!this.builderMode) {
            this.removeValueIcon.forEach((removeValueIcon, index) => {
                this.updateRemoveIcon(index);

                const removeValueHandler = () => {
                    const searchInput = this.searchInput?.[index];
                    this.clearPerson(searchInput, index);

                    if (searchInput) {
                        searchInput.focus();
                    }
                };

                this.addEventListener(removeValueIcon, 'click', removeValueHandler);
                this.addEventListener(removeValueIcon, 'keydown', ({ key }) => {
                    if (key === 'Enter') {
                        removeValueHandler();
                    }
                });
            });
        }

        return result;
    }

    addChildComponent(component) {
        component.customConditional = ChildConditional;
    }

    redraw() {
        const modeSwitcherInFocus = (this.modeSwitcher && (document.activeElement === this.modeSwitcher));

        return super.redraw()
            .then((result) => {
                if (modeSwitcherInFocus && this.modeSwitcher) {
                    this.modeSwitcher.focus();
                }

                return result;
            });
    }

    clearPerson(element, index, searchValue = '') {
        console.log('Cleaing person!');
        console.log({element, index, searchValue});

        if (!this.isEmpty()) {
            this.triggerChange();
        }

        if (this.person?.[index]) {
            console.log('doign the thing for an indexc');
            this.person[index] = this.emptyValue;
            this.person[index].display = searchValue;
        } else {
            console.log('doign the thing for an individual');
            this.person = this.emptyValue;
            this.person.display = searchValue;
        }

        if (element) {
            element.person = searchValue;
        }

        this.updateRemoveIcon(index);
    }

    getDisplayValue(value = this.person) {
        return !this.manualMode
            ? value?.display
            : '';
    }

    validateMultiple() {
        return this.isMultiple;
    }

    updateRemoveIcon(index) {
        const removeValueIcon = this.removeValueIcon?.[index];
        if (removeValueIcon) {
            const value = this.isMultiple ? this.person[index] : this.person;
            if (this.isEmpty(value) || this.disabled) {
                this.addClass(removeValueIcon, RemoveValueIconHiddenClass);
            }
            else {
                this.removeClass(removeValueIcon, RemoveValueIconHiddenClass);
            }
        }
    }

    getValueAsString(value, options) {
        if (!value) {
            return '';
        }

        const normalizedValue = this.normalizeValue(value);

        const {
            person,
            mode,
        } = (
            this.manualModeEnabled
                ? normalizedValue
                : {
                    person: normalizedValue,
                    mode: DirectoryLookupMode.DirectorySearch
                }
        );
        const valueInManualMode = (mode === DirectoryLookupMode.Manual);

        if (!valueInManualMode) {
            return this.getDisplayValue(person);
        }

        if (valueInManualMode) {
            if (this.component.manualModeViewString) {
                return this.interpolate(this.component.manualModeViewString, {
                    person,
                    data: this.data,
                    component: this.component,
                });
            }

            return this.getComponents()
                .filter((component) => component.hasValue(person))
                .map((component) => [component, _.get(person, component.key)])
                .filter(([component, componentValue]) => !component.isEmpty(componentValue))
                .map(([component, componentValue]) => component.getValueAsString(componentValue, options))
                .join(', ');
        }

        return super.getValueAsString(person, options);
    }

    focus() {
        if (this.searchInput && this.searchInput[0]) {
            this.searchInput[0].focus();
        }
    }

    isEmpty(value = this.dataValue) {
        if (value && !value.person) {
            return true;
        }

        return super.isEmpty(value?.person?.netid);
    }

    static indicatorDefaultTemplate() {
        return '<i class="fas fa-graduation-cap fa-fw" aria-hidden="true"></i>';
    }

    static indicatorAjaxTemplate() {
        return '<span class="text-muted mr-2">Checking directory...</span>'
             + '<i class="fas fa-spinner fa-pulse fa-fw" aria-hidden="true"></i>';
    }

    static indicatorResultTemplate(value = {}) {
        if (value.person) {
            return `<span class="mr-2">${value.person.name}</span>`
                 + '<i class="fas fa-graduation-cap fa-fw" aria-hidden="true"></i>';
        }

        return '<span class="mr-2">Not Found</span>'
             + '<i class="fas fa-user-times fa-fw" aria-hidden="true"></i>';
    }
}
