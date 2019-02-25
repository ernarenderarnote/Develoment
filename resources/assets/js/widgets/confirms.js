if ($('.js-confirm')) {
  require('myclabs.jquery.confirm/jquery.confirm');

  $('body').on('click', 'button.js-confirm:not(.js-ajax-link)', function (e) {
    e.preventDefault();
    var button = $(this);

    var form = button.parents('form');

    $.confirm({
      button: button,
      post: false,
      confirmButtonClass: 'btn btn-warning',
      cancelButtonClass: 'btn btn-default',
      confirm: function() {
        form.off();
        form.submit();
      }
    });
  });

  $('body').on('click', 'a.js-confirm:not(.js-ajax-link)', function(e) {
    e.preventDefault();
    var a = $(this);

    $.confirm({
      button: a,
      post: false,
      confirmButtonClass: 'btn btn-warning',
      cancelButtonClass: 'btn btn-default',
      confirm: function() {
        window.location.href = a.attr('href');
      }
    });
  });
}
