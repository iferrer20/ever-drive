

// Modal
var shadow = $('.shadow');
function modalSetArgs(modal, args) {
    for (let i=0; i<args.length; i++) {
        modal.find('span.modal-arg-' + (i+1)).text(args[i]);
        modal.find('input.modal-arg-' + (i+1)).val(args[i]);
    }
}

$('[modal-open]').each(function() {
    let modal = $('#' + $(this).attr('modal-open'));
    $(this).click(() => {
        let args = $(this).attr('modal-args')?.split('|') ?? [];
        modalSetArgs(modal, args);
        modal.add(shadow).removeClass('hidden');
        $(document.body).css('overflow', 'hidden');
    });
});

$('.modal-close').click(() => {
    $('.modal').add(shadow).addClass('hidden');
    $(document.body).css('overflow', '');
});

$('.timedate').each(function() {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    $(this).text(new Date($(this).html()).toLocaleDateString('es-ES', options));
});

function modalOpen(id, ...args) {
    let modal = $('#' + id);
    modal.add(shadow).removeClass('hidden');
    $(document.body).css('overflow', 'hidden');
}