

function selectEntry(entry) {
    $('.selected').removeClass('selected');  
    entry.addClass('selected');

    $('.input-selected-entry').val($('.selected .name').text());
}

function deselectEntry() {
    $('.selected').removeClass('selected');  
}

function openEntry(entry) {
    let filename = $(entry).find('.name').text()
    let pathname = window.location.pathname;
    
    if (filename) {
        if (pathname[pathname.length-1] != '/') 
            window.location.href += '/' + filename;
        else
            window.location.href = filename;
    }
}

var lastClick = {};
const entries = $('.explorer .entry');
entries.click(function() {
    let selectedEntry = $(this);
    console.log();
    selectEntry(selectedEntry);
    if (Date.now()-lastClick.t < 400 && entries.index(selectedEntry) == entries.index(lastClick.selected)) {
        openEntry(selectedEntry);
    }
    lastClick = { t: Date.now(), selected: $(this) };
});

$(document).keydown(function(event) {
    let selected = $('.selected');
    let newSelected = '';
    if (!$('.explorer tr').length) return

    switch (event.key) {
        case 'j':
        case 'ArrowDown':
            if (!selected.length) {
                newSelected = entries.first();
                break;
            }
            newSelected = selected.next();
            break;
    
        case 'k':
        case 'ArrowUp':
            newSelected = selected.prev();
            break;
        default:
            break;

        case 'Enter':
            openEntry(selected);
            break;

    }

    if (newSelected.length) selectEntry(newSelected);
});

if ($('#ask-password').length) modal_open('#ask-password');

if ($('.explorer').length) {
    let current_context_menu;
    let context_menu_default = $('#context-menu-default');
    let context_menu_folder = $('#context-menu-folder');
    let context_menu_file = $('#context-menu-file');

    function showContextMenu(cm, event) {
        current_context_menu?.addClass('hidden');
        cm.css('transform', `translate(${event.clientX}px, ${event.clientY}px)`);
        cm.removeClass('hidden');
        current_context_menu = cm;
    }
    
    $('body').contextmenu(event => {
        if (event.target != document.body && event.target.className != 'explorer') return;
        deselectEntry();
        showContextMenu(context_menu_default, event);
        return false;
    }).click((event) => {
        current_context_menu?.addClass('hidden');
        if (event.target == document.body) deselectEntry();
    });

    $('.entry').contextmenu(event => {
        selectEntry($(event.currentTarget));
    });

    $('.entry.folder').contextmenu(event => {
        showContextMenu(context_menu_folder, event);
        return false;
    });

    $('.entry.file').contextmenu(event => {
        showContextMenu(context_menu_file, event);
        return false;
    })

    $('.create-folder').click(() => $('button[modal-open=modal-create-folder]').click());
    $('.submit-file').click(() => $('#input-file').click());
    $('#input-file').change(() => $('#submit-form').submit());

    $('.del-folder').click(() => modal_open('modal-del-folder'));
    $('.del-file').click(() => modal_open('modal-del-file'));
}

