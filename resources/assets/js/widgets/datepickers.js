//if ($('.js-datepicker').size()) {
  require('eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js');
  
  $('.js-datepicker').each(function() {
    var input = $(this);
    //console.log(input);
    input.datetimepicker({
      format: 'DD/MM/YYYY',
      useCurrent: false
    });
  });
//}
