const
  tabs = require('vue-strap/dist/vue-strap.min').tabset,
  tab = require('vue-strap/dist/vue-strap.min').tab;
  
Vue.component('store-settings', {
  
  components: {
    tabs,
    tab
  },

  data() {
    return {
      activeTab: sessionStorage.getItem('mntz.store-settings.tab')
        ? parseInt(sessionStorage.getItem('mntz.store-settings.tab'))
        : 0,
      settings: App.data.StoreSettings
    }
  },
  
  watch: {
    activeTab(val) {
      sessionStorage.setItem('mntz.store-settings.tab', val);
    }
  },
  
  ready() {
    $(document).ready(() => {
      $('input:checkbox, input:radio').on('ifChanged', (e) => {
        let
          name = $(e.target).attr('name'),
          settings = Object.assign({}, this.settings);
        
        settings[name] = $(e.target).is(':checked');
        this.settings = settings;
      });
    });
  },
  
  methods: {}
});
