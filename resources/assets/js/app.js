
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */

require('spark-bootstrap');
window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}
window.URI = require('uri-js');
window._ = require('underscore');
window.moment = require('moment');
window.Promise = require('promise');
window.Cookies = require('js-cookie');
window.Vue.filter('trans', (key) => {
  return window.Lang.get(key);
});

window.Vue.filter('url', (url) => {
  return window.App.url(url);
});
require('./components/bootstrap');

require('./widgets');

require('./app-mixin');

require('./components/store/sync');

require('./components/store/settings');
require('./components/order/order-shipping-update-form');
require('./components/order/order-review-update-form');
require('./components/order/order-index');
const
  modal = require('vue-strap/dist/vue-strap.min').modal,
  { mapGetters, mapActions } = require('vuex');
  
const store = require('./vuex/store');

const sync = require('./components/store/sync.js');
require('./components/library/files-library-component.js');
require('./components/product/add-product-wizard.js');
//require('./spark-components/bootstrap');
Vue.component(
  'add-product-wizard-categories',
  require('./components/dashboard/product/add-product-wizard-categories.vue')
);

window.noty = require('noty');

window.Ladda = require('ladda');

require('sweetalert');

App.models = require('./models'); 

App.url = (url) => {
  return App.urls.root + url;
};

var email = document.querySelector('#session').value;

Spark.forms.register = {
    email: email
};


var app = new Vue({
    mixins: [require('spark')],
    store,

    components: {
      modal,
    },

    data: {
        showAddProductModal: false,
        
    },

    ready() {
    if (this.user) {
      App.models.product.getCategories((response) => {
        this.getProductCategories(response.data.categories);
        this.getCatalogAttributes(response.data.attributes);
      });
    }
  },

  methods: {
    ...mapActions([
      'getProductCategories',
      'getCatalogAttributes'
    ])
  }
});
window.Vue.config.devtools = Config.get('app.debug');
window.Vue.config.debug = Config.get('app.debug');

