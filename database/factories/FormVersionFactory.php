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
     * Holds a big string of Form.io definition JSON.
     */
    private function formio(): string
    {
        return '{"components":[{"html":"<p>Welcome to the Nick I. Evans Memorial Grant program, presented with support by an endowment from the ghost of Nick I. Evans. This program provides funding to Northwestern undergraduates who aspire to destroy the tech industry and all of its dumb ideas.</p><p>Recipients receive a <strong>$4,800 grant</strong> that covers the cost of one MacBook and living expenses for the summer. At the conclusion of the program, recipients should have bankrupted at least six blockchain startups and one Silicon Valley venture capital firm.</p><p>For questions, please contact nick.evans@northwestern.edu, the definitely-deceased individual who has made this grant possible.</p>","label":"Content","refreshOnChange":false,"key":"content","type":"content","input":false,"tableView":false},{"label":"HTML","tag":"br","attrs":[{"attr":"","value":""}],"refreshOnChange":false,"key":"html","type":"htmlelement","input":false,"tableView":false},{"html":"<h3>Applicant Info</h3>","label":"Content","refreshOnChange":false,"key":"content1","type":"content","input":false,"tableView":false},{"label":"Applicant Basics","columns":[{"components":[{"label":"Full Name","placeholder":"Grant Man","tableView":true,"validate":{"required":true},"key":"applicantName","type":"textfield","input":true,"hideOnChildrenHidden":false},{"label":"Student or Employee ID","inputMask":"9999999","tableView":true,"validate":{"required":true,"minLength":7,"maxLength":7},"key":"studentOrEmployeeId","type":"textfield","input":true,"hideOnChildrenHidden":false},{"label":"Class","widget":"html5","tableView":true,"data":{"values":[{"label":"First Year","value":"firstYear"},{"label":"Sophmore","value":"sophmore"},{"label":"Junior","value":"junior"},{"label":"Senior","value":"senior"}]},"selectThreshold":0.3,"validate":{"required":true},"key":"class","type":"select","indexeddb":{"filter":{}},"input":true,"hideOnChildrenHidden":false}],"width":6,"offset":0,"push":0,"pull":0,"size":"md"},{"components":[{"label":"Email Address","placeholder":"Grant.Man.2021@u.northwestern.edu","tableView":true,"validate":{"required":true},"key":"applicantEmail","type":"email","input":true,"hideOnChildrenHidden":false},{"label":"Phone Number","placeholder":"(999) 999-9999","tableView":true,"validate":{"required":true},"key":"applicantPhone","type":"phoneNumber","input":true,"hideOnChildrenHidden":false},{"label":"Campus Address","tableView":false,"provider":"nominatim","validate":{"required":true},"key":"campusAddress","type":"address","input":true,"components":[{"label":"Address 1","tableView":false,"key":"address1","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"},{"label":"Address 2","tableView":false,"key":"address2","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"},{"label":"City","tableView":false,"key":"city","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"},{"label":"State","tableView":false,"key":"state","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"},{"label":"Country","tableView":false,"key":"country","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"},{"label":"Zip Code","tableView":false,"key":"zip","type":"textfield","input":true,"customConditional":"show = _.get(instance, \'parent.manualMode\', false);"}],"hideOnChildrenHidden":false}],"width":6,"offset":0,"push":0,"pull":0,"size":"md"}],"key":"applicantBasics","type":"columns","input":false,"tableView":false},{"label":"HTML","tag":"br","attrs":[{"attr":"","value":""}],"refreshOnChange":false,"key":"html1","type":"htmlelement","input":false,"tableView":false},{"html":"<h3>Proposal</h3>","label":"Content","refreshOnChange":false,"key":"content2","type":"content","input":false,"tableView":false},{"label":"Proposal","editor":"ckeditor","hideLabel":true,"tableView":true,"validate":{"required":true},"key":"proposal","type":"textarea","input":true},{"label":"GithUb Profile","placeholder":"https://github.com/NIT-Adninistrative-Systems","tableView":true,"validate":{"required":true},"key":"githUbProfile","type":"url","input":true},{"label":"HTML","tag":"br","attrs":[{"attr":"","value":""}],"refreshOnChange":false,"key":"html2","type":"htmlelement","input":false,"tableView":false},{"html":"<h3>Terms &amp; Conditions</h3><p>If you are awarded and accept the Nick I. Evans Memorial Grant, you are expected not to participate in other research or internship opportunities for the duration of the program. This year, the program runs from July 25th, 2021 to August 25th, 2021.</p><p>Due to the current COVID-19 climate, you agree not to engage in international travel for the purposes of your project. Further, you will make all efforts to comply with CDC and IL Department of Health guidelines for COVID-19 prevention during your project. Attending on-campus activities is subject to Northwestern\'s policies on weekly COVID-19 testing.</p><p>You will receive an in-depth terms &amp; conditions document if you are awarded the Nick I. Evans Memorial Grant. You may choose to decline the grant.</p>","label":"Content","refreshOnChange":false,"key":"content3","type":"content","input":false,"tableView":false},{"label":"I agree to the terms & conditions","tableView":false,"key":"iAgreeToTheTermsConditions","type":"signature","input":true},{"label":"Submit","showValidations":false,"size":"lg","block":true,"disableOnInvalid":true,"tableView":false,"key":"submit","type":"button","input":true}]}';
    }
}
