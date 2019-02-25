require('spark-bootstrap');
require('../../js/spark-components/bootstrap');

window.App.Vue = new window.Vue({
  mixins: [require('spark')]
});
window.Vue.config.devtools = Config.get('app.debug');
window.Vue.config.debug = Config.get('app.debug');
