import { defineNorthwesternConfig } from "@nu-appdev/northwestern-starlight-theme/config";

export default defineNorthwesternConfig({
    site: "https://nit-administrative-systems.github.io/dynamic-forms/",
    base: "/dynamic-forms/",
    starlight: {
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
    },
});
