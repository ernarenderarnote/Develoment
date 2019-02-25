var notification = require('../widgets/notification');

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': App.csrfToken
  }
});

var request = {
  expectJSON: true,
  
  fail: (onFail) => {
    return (jqXHR, textStatus, errorThrown) => {
      if (typeof onFail == 'function') {
        onFail({});
      }
      
      if (jqXHR.status == 301 || jqXHR.status == 302) {
        return;
      }
      
      try {
        var json = $.parseJSON(jqXHR.responseText);
        notification.error(json.message);
      }
      catch(e) {
        notification.error(jqXHR.responseText);
      }
      
      switch(jqXHR.status) {
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
          _.each(json.validationErrors, function(errors, field) {
            notification.error(errors[0]);
          });
        break;
      
        case 401:
          notification.error('Please log in to continue');
        break;
      }
    }
  },
  
  post: (url, data, onSuccess, onFail) => {
    $.post(url, data, function(response) {
      
      if (!request.expectJSON) {
        if (typeof onSuccess == 'function') {
          onSuccess(response);
          return true;
        }
      }
      
      if (typeof response == 'string') {
        
        try {
          response = $.parseJSON(response);
        }
        catch(e) {
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
      }
      else {
        notification.error(response.message);
        if (typeof onFail == 'function') {
          onFail(response);
        }
      }
    })
    .fail(request.fail(onFail));
  },
  
  postWithFormData: (url, data, onSuccess, onFail) => {
    $.ajax({
      url: url,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      type: 'POST',
      success: (response) => {
        
        if (!request.expectJSON) {
          if (typeof onSuccess == 'function') {
            onSuccess(response);
            return true;
          }
        }
        
        if (typeof response == 'string') {
          
          try {
            response = $.parseJSON(response);
          }
          catch(e) {
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
        }
        else {
          notification.error(response.message);
          if (typeof onFail == 'function') {
            onFail(response);
          }
        }
        
      }
    })
    .fail(request.fail(onFail));
  },
  
  get: (url, data, onSuccess, onFail) => {
    $.get(url, data, function(response) {
      
      if (!request.expectJSON) {
        if (typeof onSuccess == 'function') {
          onSuccess(response);
          return true;
        }
      }
      
      if (typeof response == 'string') {
        
        try {
          response = $.parseJSON(response);
        } catch(e) {
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
      }
      else {
        notification.error(response.message);
        if (typeof onFail == 'function') {
          onFail(response);
        }
      }
    })
    .fail(request.fail(onFail));
  }
};

module.exports = request;
