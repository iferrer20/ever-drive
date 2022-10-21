

// Modal
var shadow = $('.shadow');
$('button[modal-open]').each(function() {
    let modal = $('#' + $(this).attr('modal-open'));
    $(this).click(() => {
        modal.add(shadow).removeClass('hidden');
        $(document.body).css('overflow', 'hidden');
    });
});

$('.modal-close').click(() => {
    $('.modal').add(shadow).addClass('hidden');
    $(document.body).css('overflow', '');
});

function modalOpen(id) {
    let modal = $('#' + id);
    modal.add(shadow).removeClass('hidden');
    $(document.body).css('overflow', 'hidden');
}