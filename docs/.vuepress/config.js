import { viteBundler } from '@vuepress/bundler-vite'
import { defaultTheme } from '@vuepress/theme-default'
import { defineUserConfig } from 'vuepress'
import { searchProPlugin } from 'vuepress-plugin-search-pro'
import { mdEnhancePlugin } from 'vuepress-plugin-md-enhance'

export default defineUserConfig({
    title: 'Dynamic Forms',
    description: 'Dynamic Forms for Laravel',
    head: [
        ['link', { href: 'https://common.northwestern.edu/v8/icons/favicon-16.png', rel: 'icon', sizes: '16x16', type: 'image/png' }],
        ['link', { href: 'https://common.northwestern.edu/v8/icons/favicon-32.png', rel: 'icon', sizes: '32x32', type: 'image/png' }],
        ['link', { href: 'https://use.fontawesome.com/releases/v6.4.0/css/all.css', rel: 'stylesheet'}],
    ],
    pagePatterns: ['**/*.md', '!**/README.md', '!.vuepress', '!node_modules'],
    base: '/dynamic-forms/',

    bundler: viteBundler(),
    theme: defaultTheme({
        repo: 'NIT-Administrative-Systems/dynamic-forms',
        docsBranch: 'develop',
        docsDir: 'docs',
        editLink: true,
        editLinkText: 'Edit Page',
        lastUpdated: true,
        sidebar: [
            { text: 'Overview', link: '/' },
            '/install',
            '/upgrading',
            '/usage',
            '/uploads',
            '/extending',
        ],
    }),
    plugins: [
        searchProPlugin({
            indexContent: true,
            searchDelay: 500,
            autoSuggestions: false,
        }),
        mdEnhancePlugin({
            tabs: true,
            footnote: true,
            mark: true,
            include: true,
        })
    ],
})
