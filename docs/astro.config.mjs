import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';
import northwesternTheme from "@nu-appdev/northwestern-starlight-theme";

// https://astro.build/config
export default defineConfig({
    site: "https://nit-administrative-systems.github.io/dynamic-forms/",
    integrations: [
        starlight({
            plugins: [northwesternTheme()],
            title: "Dynamic Forms for Laravel",
            editLink: {
                baseUrl: "https://github.com/NIT-Administrative-Systems/dynamic-forms/edit/develop/docs/",
            },
            sidebar: [
                {
                    label: "Overview",
                    link: '/'
                },
                {
                    label: "Setup",
                    autogenerate: { directory: "setup" },
                },
                {
                    label: "Usage",
                    autogenerate: { directory: "usage" },
                },
            ],
            social: [
                {
                    icon: "github",
                    label: "GitHub",
                    href: "https://github.com/NIT-Administrative-Systems/dynamic-forms",
                },
            ],
        }),
    ]
});
