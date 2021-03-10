<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\FormVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormVersionFactory extends Factory
{
    protected $model = FormVersion::class;

    public function definition()
    {
        return [
            'form_id' => Form::factory(),
            'published_at' => $this->faker->dateTimeThisYear(),
            'definition' => $this->formio(),
        ];
    }

    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return ['published_at' => null];
        });
    }

    /**
     * Holds a big string of Form.io definition JSON
     */
    private function formio(): string
    {
        return '{"components":[{"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"First Name","key":"firstName","placeholder":"Enter your first name","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":false,"when":null,"eq":""},"type":"textfield","$$hashKey":"object:18","autofocus":false,"hidden":false,"clearOnHide":true,"spellcheck":true},{"input":true,"tableView":true,"inputType":"text","inputMask":"","label":"Last Name","key":"lastName","placeholder":"Enter your last name","prefix":"","suffix":"","multiple":false,"defaultValue":"","protected":false,"unique":false,"persistent":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":"","customPrivate":false},"conditional":{"show":false,"when":null,"eq":""},"type":"textfield","$$hashKey":"object:19","autofocus":false,"hidden":false,"clearOnHide":true,"spellcheck":true},{"input":true,"tableView":true,"label":"Message","key":"message","placeholder":"What do you think?","prefix":"","suffix":"","rows":3,"multiple":false,"defaultValue":"","protected":false,"persistent":true,"validate":{"required":false,"minLength":"","maxLength":"","pattern":"","custom":""},"type":"textarea","conditional":{"show":false,"when":null,"eq":""},"$$hashKey":"object:20","autofocus":false,"hidden":false,"wysiwyg":false,"clearOnHide":true,"spellcheck":true},{"type":"button","theme":"primary","disableOnInvalid":true,"action":"submit","block":false,"rightIcon":"","leftIcon":"","size":"md","key":"submit","tableView":false,"label":"Submit","input":true,"$$hashKey":"object:21","autofocus":false}],"display":"form","page":0}';
    }
}
