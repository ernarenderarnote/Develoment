//if ($('input:checkbox, input:radio').size()) {
  require('icheck/icheck.min.js');

  $('input:checkbox, input:radio')
    // TODO: maybe will be needed later
    //.on('ifCreated ifClicked ifChanged ifChecked ifUnchecked ifDisabled ifEnabled ifDestroyed check', (event) => {
    //  console.log('iCheck event', event);
    //  if(event.type ==="ifChecked"){
    //    $(this).trigger('click');
    //    $(this).trigger('change');
    //    $('input').iCheck('update');
    //  }
    //  if(event.type === "ifUnchecked") {
    //    $(this).trigger('click');
    //    $(this).trigger('change');
    //    $('input').iCheck('update');
    //  }
    //  if(event.type === "ifDisabled") {
    //    $('input').iCheck('update');
    //  }
    //})
    .iCheck({
      labelHover: false,
      cursor: true,
      checkboxClass: 'icheckbox_flat-grey',
      radioClass: 'iradio_flat-grey'
    });
//}
