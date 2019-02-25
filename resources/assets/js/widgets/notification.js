$(document).ready(function() {
  $.noty.defaults.layout = 'topRight';
});

module.exports = {
  error: function(message, timeout) {
    if (typeof timeout == 'undefined') {
      timeout = 7000;
    }
    
    noty({
      text: message,
      type: 'error',
      timeout: timeout
    });
  },
  
  success: function(message, timeout) {
    if (typeof timeout == 'undefined') {
      timeout = 7000;
    }
    
    noty({
      text: message,
      type: 'success',
      timeout: timeout
    });
  }
};
