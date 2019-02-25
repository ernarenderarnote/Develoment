if (typeof window.App == 'undefined') {
  window.App = {};
}

window.$ = window.jQuery = require('jquery');
window.noty = require('noty');
window.Ladda = require('ladda');
require('sweetalert');

require('jquery-slimscroll/jquery.slimscroll.min');

require('admin-lte/dist/js/adminlte.min'); 

// app
require('./widgets');

App.models = require('./../../js/models');

App.url = (url) => {
  return App.urls.root + url;
};

require('./vue-bootstrap');
