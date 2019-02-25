var Grapnel = require('grapnel');

Vue.component('attach-product-variant-modal', {

  ready() {
    $(document).ready(() => {

      var
        self = this,
        router = new Grapnel();

      router.get('/attach-product-variant/:id', this.routing);

      this.$nextTick(() => {
        $('#js-attach-product-variant-modal', self.root).on('hidden.bs.modal', (e) => {
          router.navigate('/');
          self.productVariant = null;
        });
      });

    });
  },

  data() {
    return {
      productVariant: null
    }
  },

  methods: {

    onVariantLoadSuccess(response) {
      //console.log('onVariantLoadSuccess', response);
      this.$nextTick(() => {
        this.productVariant = response.data.productVariant;
        this.$children[0].setProductVariant(this.productVariant);
        $('#js-attach-product-variant-modal', this.$root.el).modal('show');
      });
    },

    routing(request) {
      var id = request.params.id;
      if (id) {
        App.models.productVariant.getVariant(id, this.onVariantLoadSuccess, this.onFail);
      }
    },

    onFail() {}
  }
});
