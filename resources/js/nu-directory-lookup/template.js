import DirectoryLookup from './directory-lookup'

export default {
    form: (ctx) => {
        let appendId = `${ctx.instance.id}-${ctx.component.key}-indicator`;
        let inputAttrs = [];

        if (ctx.displayValue) {
            inputAttrs.push(`value="${ctx.displayValue}"`);
        }

        for (let attr in ctx.inputAttributes) {
            inputAttrs.push(`${attr}="${ctx.inputAttributes[attr]}"`);
        }

        let template = `<div class="input-group mb-3">`;

        template += `<input
                        ref="${ctx.ref.searchInput}"
                        ${inputAttrs.join(' ')}
                        id="${ctx.instance.id}-${ctx.component.key}"
                        aria-describedby="${appendId}"
                     >`;

        template += `<div class="input-group-append" style="max-width: 60%">`
                 +      `<span class="input-group-text" id="${appendId}" ref="indicator">`;


        if (ctx.rawValue.display) {
            template += DirectoryLookup.indicatorResultTemplate(ctx.rawValue);
        } else {
            template += DirectoryLookup.indicatorDefaultTemplate();
        }

        template +=      `</span>`
                 +  `</div>`;

        template += `</div>`;

        return template.trim();
    }
}
