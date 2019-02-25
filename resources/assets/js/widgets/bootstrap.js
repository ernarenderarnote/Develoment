// tabs
$('.js-tabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

$('.js-popover, [data-toggle="dropdown"]').each(function() {
  let
    p = $(this),
    isHtml = !!p.attr('data-content-selector');
 /*  p.popover({
    html: isHtml,
    content() {
      return p.attr('data-content-selector')
        ? $(p.attr('data-content-selector')).html()
        : p.attr('data-content');
    }
  }); */
});

// modals
//$('.js-attach-product-variant-modal').on('shown.bs.modal', function(e) {
//  var product_variant_id = $(this).data('product_variant_id');
//});
