////if ($('time').length()) {
  var moment = require('moment/moment.js');
  
  var now = moment();
  var elements = $('time');
  
  elements.each(function(i, e) {
    var time = moment($(e).attr('datetime'));
    $(e).attr('title', time.format('MMMM Do YYYY, h:mm:ss a'));
    
    if ($(e).data('format')) {
      $(e).html('<span>' + time.format($(e).data('format')) + '</span>');
    }
    else {
      $(e).html('<span>' + time.from(now) + '</span>');
    }
  });
//}
