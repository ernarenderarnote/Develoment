<script lang="babel">
const
  modal = require('vue-strap/dist/vue-strap.min').modal,
  spinner = '';

module.exports = {
  name: 'direct-order-modal',
  
  components: {
    modal,
    spinner
  },
  
  data() {
    return {
      showDirectOrderModal: false
    };
  },
  
  computed: {
    stores() {
      return this.$store.state.stores
    }
  },
  
  ready() {
    this.$refs.spinner.show();
    this.getStores()
      .then(() => this.$refs.spinner.hide())
      .catch(() => this.$refs.spinner.hide());
  },
  
  methods: {
    getStores() {
      return this.$store.dispatch('getStores');
    }
  }
};
</script>

<template>
  
<div class="d-ib">
  <a
    @click.prevent="showDirectOrderModal = true"
    href="#!"
    class="h-50 d-ib p-15">
      <i class="fa fa-fw fa-btn fa-shopping-cart"></i>
      {{ 'labels.direct_order' | trans }}
  </a>
      
  <modal
    :show.sync="showDirectOrderModal"
    :title="'labels.direct_order' | trans"
    large="true">
    <div-- slot="modal-body" class="modal-body">
      
      <spinner v-ref:spinner size="md"></spinner>
      
      <div v-if="stores.length > 0" class="row">
        <!--div
          v-for="store in stores"
          class="col-xs-12 col-md-6">
          <a
            :href="'/dashboard/orders/create?store_id='+store.id"
            class="d-b thumbnail ta-c pt-50 h-200">
              <h3>{{ store.name }}</h3>
              <div class="text-muted">{{'labels.products' | trans }}: {{ store.productsCount }}</div>
          </a>
        </div-->
      </div>
      
      <div v-else class="alert alert-warning">
        <h3 class="ta-c mtb-20">{{ 'labels.you_have_no_stores' | trans }}</h3>
      </div>
      
    </div>
    <div slot="modal-footer" class="modal-footer d-n"></div>
  </modal>
</div>

</template>
