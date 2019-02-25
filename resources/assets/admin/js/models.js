import request from 'js/libraries/request';

module.exports = {
  priceModifiers: {
    add(data, onSuccess, onFail) {
      request.post('/admin/price-modifiers/add', data, onSuccess, onFail);
    },
    delete(id, onSuccess, onFail) {
      request.post('/admin/price-modifiers/'+id+'/delete', {}, onSuccess, onFail);
    }
  }
};
