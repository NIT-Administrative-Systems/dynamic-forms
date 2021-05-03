module.exports = {
    base: '/dynamic-forms/',
    title: 'Dynamic Forms',
    description: 'Dynamic Forms for Laravel',
    dest: '.build',

    themeConfig: {
        displayAllHeaders: true,
        repo: 'NIT-Administrative-Systems/dynamic-forms',
        docsDir: 'docs',
        docsBranch: 'develop',
        editLinks: true,
        editLinkText: 'Edit Page',
        lastUpdated: true,

        sidebar: [
            ['/', 'Overview'],
            './install',
            './usage',
            './uploads',
        ],
    },
}
