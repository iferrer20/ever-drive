

// Modal
var modal_shadow = $('.modal-shadow');
$('button[modal-open]').each(function() {
  let modal = $('#' + $(this).attr('modal-open'));
  $(this).click(() => {
    modal.add(modal_shadow).addClass('displayed');
    $(document.body).css('overflow', 'hidden');
  });
});

$('.modal-close').click(() => {
  $('.modal').add(modal_shadow).removeClass('displayed');
  $(document.body).css('overflow', '');
});
