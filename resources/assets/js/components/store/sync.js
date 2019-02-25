const
  tabs = require('vue-strap/dist/vue-strap.min').tabset,
  tab = require('vue-strap/dist/vue-strap.min').tab,
  modal = require('vue-strap/dist/vue-strap.min').modal,
  loading = require('../../vendor/vue-loading.js'),
  loadAwesome = require('../../libraries/load-awesome.js');

Vue.component('store-sync', {
  
    components: {
      modal,
      tabs,
      tab
    },
    
  
    ready() {
      
    },
    
    directive(){
      loading
    },

    data() {
      return {
        activeTab: sessionStorage.getItem('mntz.store-sync.tab')
        ? parseInt(sessionStorage.getItem('mntz.store-sync.tab'))
        : 0,
        showAddProductModal: false,
        updateProduct: null,
        addProductWizardLoading: false,
        activeTab:0,
      }
    },
    
    watch: {
      activeTab(val) {
        sessionStorage.setItem('mntz.store-sync.tab', val);
      }
    },
    
    methods: {
    
      showUpdateProductModal(product_id) {
        this.showAddProductModal = true;
        this.addProductWizardLoading = true;
        App.models.product.getProduct(product_id, (response) => {
          this.addProductWizardLoading = false;
          this.updateProduct = response.data.product;
        },
        () => {
          this.showAddProductModal = false;
          this.addProductWizardLoading = false;
        });
      }
    },

    
  });
 
 