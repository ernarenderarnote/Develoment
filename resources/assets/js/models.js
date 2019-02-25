const request = require('./libraries/request');
module.exports = {
  product: {
    sendOnModeration(data, onSuccess, onFail) {
      request.post('/dashboard/products/send-to-moderation', data, onSuccess, onFail);
    },
    pushProduct(id, data, onSuccess, onFail) {
      request.post('/dashboard/products/'+id+'/push', data, onSuccess, onFail);
    },
    getCategories(onSuccess, onFail) {
      request.get('/dashboard/products/categories', {}, onSuccess, onFail);
    },
    getProductModelTemplate(id, onSuccess, onFail) {
      request.get('/dashboard/products/template/'+id, {}, onSuccess, onFail);
    },
    getProduct(id, onSuccess, onFail) {
      request.get('/dashboard/products/'+id+'/get', {}, onSuccess, onFail);
    },
    searchByStore({ store_id, filters, page }, onSuccess, onFail) {
      request.get('/dashboard/store/'+store_id+'/products', {
        filters,
        page
      }, onSuccess, onFail);
    }
  },
  productVariant: {
    getVariant(id, onSuccess, onFail) {
      request.get('/dashboard/product-variants/'+id, {}, onSuccess, onFail);
    },
    update(id, data, onSuccess, onFail) {
      request.post('/dashboard/product-variants/'+id+'/update', data, onSuccess, onFail);
    }
  },
  order: {
    // TODO: not used so far
    //addVariant(id, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/add-variant', {}, onSuccess, onFail);
    //},

    updateVariantPrices(id, variant_id, data, onSuccess, onFail) {
      request.post('/dashboard/orders/'+id+'/update-variant/'+variant_id, data, onSuccess, onFail);
    },

    // TODO: not used so far
    //updateVariant(id, variant_id, data, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/update-variant/'+variant_id, data, onSuccess, onFail);
    //},
    //copyVariant(id, variant_id, onSuccess, onFail) {
    //  request.post('/dashboard/orders/'+id+'/copy-variant/'+variant_id, {}, onSuccess, onFail);
    //},

    attachVariants({id, variant_ids}, onSuccess, onFail) {
      request.post('/dashboard/orders/'+id+'/attach-variants/', {
        variant_ids
      }, onSuccess, onFail);
    },
    detachVariant({id, variant_id}, onSuccess, onFail) {
      request.post('/dashboard/orders/'+id+'/detach-variant/'+variant_id, {}, onSuccess, onFail);
    },
    getOrderWithNewShippingPrice({id, countryCode, shippingMethod}, onSuccess, onFail) {
      request.get('/dashboard/orders/'+id+'/get-with-new-shipping-price', {
        'country_code': countryCode,
        'shipping_method': shippingMethod
      }, onSuccess, onFail);
    },
    searchShopify({ search, store_id }, onSuccess, onFail) {
      return request.get('/admin/orders/search-shopify', { search, store_id }, onSuccess, onFail);
    },
    pullFromShopify({ external_order_id, store_id }, onSuccess, onFail) {
      return request.post('/admin/orders/pull-from-shopify', { external_order_id, store_id }, onSuccess, onFail);
    }
  },
  library: {
    prints: {
      getPage(page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/library/prints/search', filters, onSuccess, onFail);
      },
      uploadFile(data, onSuccess, onFail) {
        request.postWithFormData('/dashboard/library/prints/upload', data, onSuccess, onFail);
      }
    },
    sources: {
      getPage(page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/library/sources/search', filters, onSuccess, onFail);
      },
      uploadFile(data, onSuccess, onFail) {
        request.postWithFormData('/dashboard/library/sources/upload', data, onSuccess, onFail);
      }
    },
    deleteFile(id, onSuccess, onFail) {
      request.post('/dashboard/library/'+id+'/delete/', {}, onSuccess, onFail);
    }
  },
  reports: {
    getData(id, filters, onSuccess, onFail) {
      request.get('/dashboard/reports/'+id+'/get-data', filters, onSuccess, onFail);
    }
  },
  store: {
    getAllForUser(onSuccess, onFail) {
      request.get('/dashboard/store.json', {} , onSuccess, onFail);
    },
    products: {
      getPage(store_id, page, filters, onSuccess, onFail) {
        filters.page = page;
        request.get('/dashboard/store/'+store_id+'/products', filters, onSuccess, onFail);
      }
    }
  },
  notifications: {
    remove(id, onSuccess, onFail) {
      request.post('/dashboard/notifications/'+id+'/delete/', {}, onSuccess, onFail);
    }
  }
};
