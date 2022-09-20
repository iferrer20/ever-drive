

function selectEntry(entry) {
    $('.selected').removeClass('selected');  
    entry.addClass('selected');
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


$('button[modal-open=ask-password]').click();

if ($('.explorer').length) {
    var context_menu = $('#context-menu');
    $('body').contextmenu((event) => {
        context_menu.css('transform', `translate(${event.clientX}px, ${event.clientY}px)`);
        context_menu.removeClass('hidden');
        return false;
    }).click(() => {
        context_menu.addClass('hidden');
    });
}

$('.create-directory').click(() => $('button[modal-open=modal-create-folder]').click());
$('.submit-file').click(() => $('#input-file').click());
$('#input-file').change(() => $('#submit-form').submit());