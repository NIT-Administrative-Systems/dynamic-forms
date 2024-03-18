/**
 * Formiojs uses an older version of FontAwesome (v4?), and *also* tries to render the BS5 icons. We don't use either
 * of these -- we're on Font Awesome 6. This function is an adapter that replaces the BS5 icon adapter and returns the
 * proper class names for FA6 icons.
 *
 * Formio uses the olde Font Awesome icon class name as the key in components/etc -- this is a UI concern that leaked
 * into their backend. But it works out: if you need an idea of what the icon looks like to compare it to modern FA,
 * you can go look it up.
 *
 * Keep an eye on <https://github.com/formio/bootstrap/blob/main/lib/mjs/templates/bootstrap5/iconClass.js> when updating
 * formiojs, since they might add new icons as they find the need for 'em. They also add icons without registering them
 * in the adapter, so it's a bit of a whack-a-mole situation.
 *
 * For the most part, you want to use the `fa-solid` version of an icon. However, something with a `-o` suffix is
 * outlined, and you can get a comparable icon with `fa-regular`.
 */
export default (iconset, name, spinning) => {
    let fa6Name = name;
    switch (name) {
        case 'cog':
            fa6Name = 'fa-solid fa-gear';
            break;
        case 'copy':
            fa6Name = 'fa-solid fa-copy';
            break;
        case 'bars':
            fa6Name = 'fa-solid fa-bars';
            break;
        case 'remove':
            fa6Name = 'fa-solid fa-trash';
            break;
        case 'font':
            fa6Name = 'fa-solid fa-font';
            break;
        case 'hashtag':
            fa6Name = 'fa-solid fa-hashtag';
            break;
        case 'th-list':
            fa6Name = 'fa-solid fa-table-list';
            break;
        case 'dot-circle-o':
            fa6Name = 'fa-regular fa-circle-dot';
            break;
        case 'plus-square':
            fa6Name = 'fa-solid fa-square-plus';
            break;
        case 'phone-square':
            fa6Name = 'fa-solid fa-phone';
            break;
        case 'home':
            fa6Name = 'fa-solid fa-house';
            break;
        case 'clock-o':
            fa6Name = 'fa-solid fa-clock';
            break;
        case 'usd':
            fa6Name = 'fa-solid fa-dollar-sign';
            break;
        case 'html5':
            fa6Name = 'fa-brands fa-html5';
            break;
        case 'pencil-square-o':
            fa6Name = 'fa-regular fa-square-pen';
            break;
        case 'columns':
            fa6Name = 'fa-solid fa-table-columns';
            break;
        case 'list-alt':
            fa6Name = 'fa-solid fa-rectangle-list';
            break;
        case 'th-large':
            fa6Name = 'fa-solid fa-table-cells-large';
            break;
        case 'folder-o':
            fa6Name = 'fa-regular fa-folder-open';
            break;
        case 'square-o':
            fa6Name = 'fa-regular fa-square';
            break;
        case 'user-secret':
            fa6Name = 'fa-solid fa-user-secret';
            break;
        case 'users':
            fa6Name = 'fa-solid fa-users';
            break;
        case 'user-group':
            fa6Name = 'fa-solid fa-user-group';
            break;
        case 'folder-open':
            fa6Name = 'fa-solid fa-folder-open';
            break;
        case 'th':
            fa6Name = 'fa-solid fa-table-cells';
            break;
        case 'tasks':
            fa6Name = 'fa-solid fa-list-check';
            break;
        case 'indent':
            fa6Name = 'fa-solid fa-indent';
            break;
        case 'refresh':
            fa6Name = 'fa-solid fa-arrows-rotate';
            break;
        case 'files-o':
            fa6Name = 'fa-regular fa-copy';
            break;
        case 'wpforms':
            fa6Name = 'fa-brands fa-wpforms';
            break;
        case 'cubes':
            fa6Name = 'fa-solid fa-cubes';
            break;
        case 'plus':
            fa6Name = 'fa-solid fa-plus';
            break;
        case 'question-sign':
            fa6Name = 'fa-regular fa-circle-question';
            break;
        case 'remove-circle':
            fa6Name = 'fa-regular fa-circle-xmark';
            break;
        case 'new-window':
            fa6Name = 'fa-solid fa-up-right-from-square';
            break;
        case 'move':
            fa6Name = 'fa-solid fa-arrows-up-down-left-right';
            break;
        case 'edit':
            fa6Name = 'fa-solid fa-pencil';
            break;
        case 'time':
            fa6Name = 'fa-solid fa-clock';
            break;
        case 'terminal':
            fa6Name = 'fa-solid fa-terminal';
            break;
        case 'check-square':
            fa6Name = 'fa-regular fa-square-check'
            break;
        case 'file':
            fa6Name = 'fa-solid fa-file';
            break;
        case 'stop':
            fa6Name = 'fa-solid fa-stop';
            break;
        case 'at':
            fa6Name = 'fa-solid fa-at';
            break;
        case 'link':
            fa6Name = 'fa-solid fa-link';
            break;
        case 'calendar':
            fa6Name = 'fa-regular fa-calendar';
            break;
        case 'list':
            fa6Name = 'fa-solid fa-list';
            break;
        case 'pencil':
            fa6Name = 'fa-solid fa-signature';
            break;
        case 'table':
            fa6Name = 'fa-solid fa-table';
            break;
        case 'save':
            fa6Name = 'fa-solid fa-save';
            break;
        case 'pdf':
            fa6Name = 'fa-solid fa-file-pdf'
            break;
        case 'docx':
            fa6Name = 'fa-solid fa-file-word';
            break;
        case 'graduation-cap':
            fa6Name = 'fa-solid fa-graduation-cap';
            break;
        case 'key':
            fa6Name = 'fa-solid fa-key';
            break;
        case 'asterisk':
            fa6Name = 'fa-solid fa-asterisk';
            break;
        case 'search':
            fa6Name = 'fa-solid fa-search';
            break;
    }

    return spinning
        ? `fa-spin fa-spinner ${fa6Name}`
        : fa6Name;
};
