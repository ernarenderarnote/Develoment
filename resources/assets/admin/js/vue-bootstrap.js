if (window.Vue === undefined) {
    window.Vue = require('vue');
    window.Bus = new Vue();
  }
  
  // global mixin
  require('../../js/app-mixin');
  
  window.Vue.filter('trans', (key) => {
    return window.Lang.get(key);
  });
  
  window.Vue.filter('url', (url) => {
    return window.App.url(url);
  });
  require('admin-lte/bower_components/chart.js/Chart.min');
  require('admin-lte/bower_components/raphael/raphael.min');
  require('admin-lte/bower_components/morris.js/morris.min.js');
  require('admin-lte/bower_components/jquery-knob/dist/jquery.knob.min.js');
  //require('ladda/dist/ladda.jquery.min.js');
  //require('ladda/dist/ladda.min.js');
  require('jasny-bootstrap/dist/js/jasny-bootstrap.min.js');
  //components   require('./components/product-model/update-color-form');
  require('./components/product-variant/admin-product-variant-show');
  require('./components/product/admin-product-show');
  require('./components/order/shipping-edit');
  
   /*   Vue.component( 'price-modifiers', 
      require('components/admin/product-model/price-modifiers.vue') );*/
    
  