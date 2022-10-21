

var selected_entry;
var drag_entry;
var hover_entry;

function selectEntry(entry, remove = true) {
    selected_entry = entry;
    if (remove) $('.selected').removeClass('selected');  
    entry.addClass('selected');
    $('.input-selected-entry').val($('.selected .name').text());
}

function hoverEntry(entry) {
    hover_entry = entry;
}

function deselectEntry() {
    $('.selected').removeClass('selected');  
    selected_entry = undefined;
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

function createFolder() {
    modalOpen('modal-create-folder');
}

function deleteFolder() {
    modalOpen('modal-del-folder');
}

function deleteFile() {
    modalOpen('modal-del-file');
}

function moveEntry(destination) {
    $(".input-destination").val(destination);
    $("#move-entry").click();
}

function selecFile() {
    $('#input-file').click();
}

function submiFile() {
    $('#submit-form').submit();
}


var last_click = {};
var drag = false;
const entries = $('.explorer .entry');
entries.on('mousedown', function(event) {
    drag = true;
    selectEntry($(this));
    if (Date.now()-last_click.t < 400 && entries.index(selected_entry) == entries.index(last_click.selected)) {
        openEntry($(this));
    }
    last_click = { t: Date.now(), selected: $(this), x: event.clientX, y: event.clientY };
    drag = true;
});

$(document).on('mouseup', () => {
    drag_entry?.remove();
    drag_entry = undefined;
    drag = false;

    if (hover_entry?.hasClass('folder') && !selected_entry.is(hover_entry)) {
        moveEntry(hover_entry.find('.name').text());
    }
});

entries.mouseover(function() {
    hoverEntry($(this));
    if (!drag) return;
});

entries.mouseleave(function() {
    hover_entry = undefined;
});

$(document).on('mousemove', event => {
    if (!drag) return;
    if (!drag_entry) {
        drag_entry = selected_entry.clone().addClass('drag-entry').prependTo($('.explorer table tbody'));
        drag_entry.find('.size').remove();
        drag_entry.css('border-bottom', 'none');
    }
    let entry_pos = selected_entry.offset();
    let offset = {x: event.clientX - last_click.x, y: event.clientY - last_click.y};
    drag_entry.css('transform', `translate(${entry_pos.left + offset.x}px, ${entry_pos.top + offset.y}px)`);
    drag_entry.css('width', `${selected_entry.width()}px`);
    drag_entry.css('height', `${selected_entry.height()}px`);
});

$(document).keydown(function(event) {
    let new_selected = '';
    if (!$('.explorer tr').length) return

    switch (event.key) {
        case 'j':
        case 'ArrowDown':
            if (!selected_entry) {
                new_selected = entries.first();
                break;
            }
            new_selected = selected_entry.next();
            break;
    
        case 'k':
        case 'ArrowUp':
            new_selected = selected_entry.prev();
            break;
        default:
            break;

        case 'Enter':
            openEntry(selected_entry);
            break;

    }

    if (new_selected.length) selectEntry(new_selected);
});

if ($('#ask-password').length) modalOpen('ask-password');

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

    $('.create-folder').click(createFolder);
    $('.submit-file').click(selecFile);
    $('#input-file').change(submiFile);

    $('.del-folder').click(deleteFolder); 
    $('.del-file').click(deleteFile);
}

