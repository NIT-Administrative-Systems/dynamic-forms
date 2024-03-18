import {Formio} from 'formiojs';

export default class CustomSurvey extends Formio.Components.components.survey {
    /**
     * When multiple survey components of the same kind are on the same page, they need to have unique names.
     * This prevents the browser from getting confused about which one is being interacted with and losing
     * the state of the other.
     */
    getInputName(question) {
        return `${this.id}-${this.options.name}[${question.value}]`;
    }
}
