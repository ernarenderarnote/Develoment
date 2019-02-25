/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/assets/js/app-mixin.js":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__models__ = __webpack_require__("./resources/assets/js/models.js");
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__models___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__models__);


Vue.mixin({
  data: function data() {
    return {
      // TODO: not used so far
      //currentUser: App.user
    };
  },


  computed: {
    constants: function constants() {
      return App.constants;
    },
    csrfToken: function csrfToken() {
      return App.csrfToken;
    },
    _: function (_2) {
      function _() {
        return _2.apply(this, arguments);
      }

      _.toString = function () {
        return _2.toString();
      };

      return _;
    }(function () {
      return _;
    }),
    models: function models() {
      return __WEBPACK_IMPORTED_MODULE_0__models___default.a;
    }
  },

  methods: {
    trans: function trans(key) {
      var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      return this.lang(key, params);
    },
    lang: function lang(key) {
      var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

      return Lang.get(key, params);
    },
    url: function url(_url) {
      return App.urls.root + _url;
    },
    datetime: function datetime(_datetime) {
      return moment(_datetime.date + ' ' + _datetime.timezone, 'YYYY-MM-DD HH:mm:ss Z').format('MMMM Do, YYYY h:mm A');
    },
    can: function can(permission, obj) {
      if (!Vue.config.silent && (typeof obj.policy == 'undefined' || typeof obj.policy.allowed[permission] == 'undefined')) {
        console.warn("Policy %s not found in object %o", permission, obj);
      }

      return typeof obj.policy != 'undefined' && typeof obj.policy.allowed[permission] != 'undefined' && !!obj.policy.allowed[permission];
    }
  }
});

/***/ }),

/***/ "./resources/assets/js/libraries/request.js":
/***/ (function(module, exports, __webpack_require__) {

var notification = __webpack_require__("./resources/assets/js/widgets/notification.js");

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': App.csrfToken
  }
});

var request = {
  expectJSON: true,

  fail: function fail(onFail) {
    return function (jqXHR, textStatus, errorThrown) {
      if (typeof onFail == 'function') {
        onFail({});
      }

      if (jqXHR.status == 301 || jqXHR.status == 302) {
        return;
      }

      try {
        var json = $.parseJSON(jqXHR.responseText);
        notification.error(json.message);
      } catch (e) {
        notification.error(jqXHR.responseText);
      }

      switch (jqXHR.status) {
        //case 405:
        //	noty({
        //		text: "You're trying to do something wrong",
        //		type: 'error',
        //		timeout: 7000
        //	});
        //break;
        //
        //case 404:
        //	noty({
        //		text: "Resource which you're trying to access was not found",
        //		type: 'error',
        //		timeout: 7000
        //	});
        //break;

        //case 403:
        //	noty({
        //		text: "You're not allowed to do this",
        //		type: 'error',
        //		timeout: 7000
        //	});
        //break;

        case 422:
          _.each(json.validationErrors, function (errors, field) {
            notification.error(errors[0]);
          });
          break;

        case 401:
          notification.error('Please log in to continue');
          break;
      }
    };
  },

  post: function post(url, data, onSuccess, onFail) {
    $.post(url, data, function (response) {

      if (!request.expectJSON) {
        if (typeof onSuccess == 'function') {
          onSuccess(response);
          return true;
        }
      }

      if (typeof response == 'string') {

        try {
          response = $.parseJSON(response);
        } catch (e) {
          notification.error('Unexpected server error');
          if (typeof onFail == 'function') {
            onFail(response);
          }
          return false;
        }
      }

      if (!response.isError) {
        if (response.message) {
          notification.success(response.message);
        }

        if (typeof onSuccess == 'function') {
          onSuccess(response);
        }
      } else {
        notification.error(response.message);
        if (typeof onFail == 'function') {
          onFail(response);
        }
      }
    }).fail(request.fail(onFail));
  },

  postWithFormData: function postWithFormData(url, data, onSuccess, onFail) {
    $.ajax({
      url: url,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      type: 'POST',
      success: function success(response) {

        if (!request.expectJSON) {
          if (typeof onSuccess == 'function') {
            onSuccess(response);
            return true;
          }
        }

        if (typeof response == 'string') {

          try {
            response = $.parseJSON(response);
          } catch (e) {
            notification.error('Unexpected server error');
            if (typeof onFail == 'function') {
              onFail(response);
            }
            return false;
          }
        }

        if (!response.isError) {
          if (response.message) {
            notification.success(response.message);
          }

          if (typeof onSuccess == 'function') {
            onSuccess(response);
          }
        } else {
          notification.error(response.message);
          if (typeof onFail == 'function') {
            onFail(response);
          }
        }
      }
    }).fail(request.fail(onFail));
  },

  get: function get(url, data, onSuccess, onFail) {
    $.get(url, data, function (response) {

      if (!request.expectJSON) {
        if (typeof onSuccess == 'function') {
          onSuccess(response);
          return true;
        }
      }

      if (typeof response == 'string') {

        try {
          response = $.parseJSON(response);
        } catch (e) {
          notification.error('Unexpected server error');
          if (typeof onFail == 'function') {
            onFail(response);
          }
          return false;
        }
      }

      if (!response.isError) {
        if (response.message) {
          notification.success(response.message);
        }

        if (typeof onSuccess == 'function') {
          onSuccess(response);
        }
      } else {
        notification.error(response.message);
        if (typeof onFail == 'function') {
          onFail(response);
        }
      }
    }).fail(request.fail(onFail));
  }
};

module.exports = request;

/***/ }),

/***/ "./resources/assets/js/models.js":
/***/ (function(module, exports, __webpack_require__) {

var request = __webpack_require__("./resources/assets/js/libraries/request.js");
module.exports = {
  product: {
    sendOnModeration: function sendOnModeration(data, onSuccess, onFail) {
      request.post('/dashboard/products/send-to-moderation', data, onSuccess, onFail);
    },
    pushProduct: function pushProduct(id, data, onSuccess, onFail) {
      request.post('/dashboard/products/' + id + '/push', data, onSuccess, onFail);
    },
    getCategories: function getCategories(onSuccess, onFail) {
      request.get('/dashboard/products/categories', {}, onSuccess, onFail);
    },
    getProductModelTemplate: function getProductModelTemplate(id, onSuccess, onFail) {
      request.get('/dashboard/products/template/' + id, {}, onSuccess, onFail);
    },
    getProduct: function getProduct(id, onSuccess, onFail) {
      request.get('/dashboard/products/' + id + '/get', {}, onSuccess, onFail);
    },
    searchByStore: function searchByStore(_ref, onSuccess, onFail) {
      var store_id = _ref.store_id,
          filters = _ref.filters,
          page = _ref.page;

      request.get('/dashboard/store/' + store_id + '/products', {
        filters: filters,
        page: page
      }, onSuccess, onFail);
    }
  },
  productVariant: {
    getVariant: function getVariant(id, onSuccess, onFail) {
      request.get('/dashboard/product-variants/' + id, {}, onSuccess, onFail);
    },
    update: function update(id, data, onSuccess, onFail) {
      request.post('/dashboard/product-variants/' + id + '/update', data, onSuccess, onFail);
    }
  },
  order: {
    // TODO: not used so far
    //addVariant(id, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/add-variant', {}, onSuccess, onFail);
    //},

    updateVariantPrices: function updateVariantPrices(id, variant_id, data, onSuccess, onFail) {
      request.post('/dashboard/orders/' + id + '/update-variant/' + variant_id, data, onSuccess, onFail);
    },


    // TODO: not used so far
    //updateVariant(id, variant_id, data, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/update-variant/'+variant_id, data, onSuccess, onFail);
    //},
    //copyVariant(id, variant_id, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/copy-variant/'+variant_id, {}, onSuccess, onFail);
    //},

    attachVariants: function attachVariants(_ref2, onSuccess, onFail) {
      var id = _ref2.id,
          variant_ids = _ref2.variant_ids;

      request.post('/dashboard/orders/' + id + '/attach-variants/', {
        variant_ids: variant_ids
      }, onSuccess, onFail);
    },
    detachVariant: function detachVariant(_ref3, onSuccess, onFail) {
      var id = _ref3.id,
          variant_id = _ref3.variant_id;

      request.post('/dashboard/orders/' + id + '/detach-variant/' + variant_id, {}, onSuccess, onFail);
    },
    getOrderWithNewShippingPrice: function getOrderWithNewShippingPrice(_ref4, onSuccess, onFail) {
      var id = _ref4.id,
          countryCode = _ref4.countryCode,
          shippingMethod = _ref4.shippingMethod;

      request.get('/dashboard/orders/' + id + '/get-with-new-shipping-price', {
        'country_code': countryCode,
        'shipping_method': shippingMethod
      }, onSuccess, onFail);
    },
    searchShopify: function searchShopify(_ref5, onSuccess, onFail) {
      var search = _ref5.search,
          store_id = _ref5.store_id;

      return request.get('/admin/orders/search-shopify', { search: search, store_id: store_id }, onSuccess, onFail);
    },
    pullFromShopify: function pullFromShopify(_ref6, onSuccess, onFail) {
      var external_order_id = _ref6.external_order_id,
          store_id = _ref6.store_id;

      return request.post('/admin/orders/pull-from-shopify', { external_order_id: external_order_id, store_id: store_id }, onSuccess, onFail);
    }
  },
  library: {
    prints: {
      getPage: function getPage(page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/library/prints/search', filters, onSuccess, onFail);
      },
      uploadFile: function uploadFile(data, onSuccess, onFail) {
        request.postWithFormData('/dashboard/library/prints/upload', data, onSuccess, onFail);
      }
    },
    sources: {
      getPage: function getPage(page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/library/sources/search', filters, onSuccess, onFail);
      },
      uploadFile: function uploadFile(data, onSuccess, onFail) {
        request.postWithFormData('/dashboard/library/sources/upload', data, onSuccess, onFail);
      }
    },
    deleteFile: function deleteFile(id, onSuccess, onFail) {
      request.post('/dashboard/library/' + id + '/delete/', {}, onSuccess, onFail);
    }
  },
  reports: {
    getData: function getData(id, filters, onSuccess, onFail) {
      request.get('/dashboard/reports/' + id + '/get-data', filters, onSuccess, onFail);
    }
  },
  store: {
    getAllForUser: function getAllForUser(onSuccess, onFail) {
      request.get('/dashboard/store.json', {}, onSuccess, onFail);
    },

    products: {
      getPage: function getPage(store_id, page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/store/' + store_id + '/products', filters, onSuccess, onFail);
      }
    }
  },
  notifications: {
    remove: function remove(id, onSuccess, onFail) {
      request.post('/dashboard/notifications/' + id + '/delete/', {}, onSuccess, onFail);
    }
  }
};

/***/ }),

/***/ "./resources/assets/js/widgets/notification.js":
/***/ (function(module, exports) {

$(document).ready(function () {
  $.noty.defaults.layout = 'topRight';
});

module.exports = {
  error: function error(message, timeout) {
    if (typeof timeout == 'undefined') {
      timeout = 7000;
    }

    noty({
      text: message,
      type: 'error',
      timeout: timeout
    });
  },

  success: function success(message, timeout) {
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

/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/assets/js/app-mixin.js");


/***/ })

/******/ });