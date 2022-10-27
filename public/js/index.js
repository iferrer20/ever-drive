

// Modal
var shadow = $('.shadow');
$('[modal-open]').each(function() {
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

$('.timedate').each(function() {
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    $(this).text(new Date($(this).html()).toLocaleDateString('es-ES', options));
});

function modalOpen(id) {
    let modal = $('#' + id);
    modal.add(shadow).removeClass('hidden');
    $(document.body).css('overflow', 'hidden');
}