export default {
    /**
     * This is the bar you see on-hover in the builder that has the edit, copy, delete, etc icons.
     *
     * The customazation here removes the 'Edit JSON' icon, which isn't a useful option for the target audience.
     *
     * @see https://github.com/formio/formio.js/blob/master/src/templates/bootstrap/builderComponent/form.ejs
     */
    builderComponent: {
        form: (ctx) => {
            let template = '<div class="builder-component" ref="dragComponent">';

            if (!ctx.disableBuilderActions) {
                template += `
                    <div class="component-btn-group" data-noattach="true">
                      <div class="btn btn-xxs btn-danger component-settings-button component-settings-button-remove" ref="removeComponent">
                        <i class="${ctx.iconClass('remove')}"></i>
                      </div>
                      <div class="btn btn-xxs btn-default component-settings-button component-settings-button-copy" ref="copyComponent">
                        <i class="${ctx.iconClass('copy')}"></i>
                      </div>
                      <div class="btn btn-xxs btn-default component-settings-button component-settings-button-paste" ref="pasteComponent">
                        <i class="${ctx.iconClass('save')}"></i>
                      </div>
                      <div class="btn btn-xxs btn-default component-settings-button component-settings-button-move" ref="moveComponent">
                        <i class="${ctx.iconClass('move')}"></i>
                      </div>
                      <div class="btn btn-xxs btn-secondary component-settings-button component-settings-button-edit", ref="editComponent">
                        <i class="${ctx.iconClass('cog')}"></i>
                      </div>
                    </div>
                `;
            }

            template += ctx.html;
            template += '</div>';

            return template.trim();
        },
    }
}
