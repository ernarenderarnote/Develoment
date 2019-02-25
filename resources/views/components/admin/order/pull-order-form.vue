<template>
<div class="top-order-form">
  <spinner v-ref:spinner size="md"></spinner>

  <div class="d-ib va-m">
    {{ lang('labels.order_import') }}
  </div>

  <div class="d-ib va-m">
    <select v-model="selectedStoreId" class="form-control" name="store_id">
      <!--option v-for="store in stores" :value="store.id">{{ store.name }}</option-->
    </select>
  </div>

  <div class="d-ib va-m">
    <multiselect
      :allow-empty="false"
      :close-on-select="true"
      :clear-on-select="true"
      :options="externalOrders"
      :selected.sync="selectedOrder"
      :searchable="true"
      :disabled="!selectedStoreId"
      :multiple="false"
      option-partial="customOptionPartial"
      select-label=""
      selected-label=""
      :loading="isLoading"
      :placeholder="lang('labels.order_number')"
      key="id"
      label="name"
      @search-change="search"
      @update="onOptionSelected"
    ></multiselect>
  </div>

  <button
    :disabled="!selectedStoreId || !selectedOrder || !selectedOrder.id"
    class="btn btn-primary va-m"
    @click="pull">
      {{ lang('labels.pull_order') }}
  </button>

</div>
</template>

<script>

const
 spinner = require('components/vendor/vue-strap/Spinner.vue'),
 multiselect = require('js/vendor/vue-multiselect');

const
  notification = require('js/widgets/notification'),
  variantOptionTemplate = `<div>
    <div class="option__desc">
      <span class="option__title">{{ option.name }} ({{ option.status }})</span>
    </div>
  </div>`;

Vue.partial('customOptionPartial', variantOptionTemplate);

module.exports = {

  name: 'admin-pull-order-form',

  data() {
    return {
      stores: App.data.shopifyStores,
      selectedOrder: {},
      selectedStoreId: null,
      term: null,
      externalOrders: [],
      currentRequest: null,
      isLoading: false
    };
  },

  components: {
     multiselect,

     spinner
  },

  methods: {

    onOptionSelected(selected) {
      this.selectedOrder = selected;
    },

    // orderLabel(order) {
    //   let
    //     action = order.exists
    //       ? this.trans('labels.reset_order')
    //       : this.trans('labels.pull_order'),
    //     orderNumber = order.name,
    //     date = this.datetime(order.created_at),
    //     status = order.status;
    //
    //   return `${action} ${orderNumber} (${date}, ${status})`;
    // },

    search(term) {

      if (!(term * 1)) {
        return;
      }

      if (this.currentRequest) {
        this.currentRequest.abort();
      }

      Vue.nextTick(() => {
        this.isLoading = true;
        this.currentRequest = this.models.order.searchShopify({
            store_id: this.selectedStoreId,
            search: term
          },
          (response) => {
            this.isLoading = false;
            this.currentRequest = null;
            this.externalOrders = response.data.externalOrders;
          },
          () => {
            this.currentRequest = null;
            this.isLoading = false;
          }
        );
      });
    },

    // pullConfirm() {
    //   MessageBox.confirm(this.trans('labels.reset_order_?'), this.trans('labels.confirmation'), {
    //     confirmButtonText: this.trans('labels.reset'),
    //     cancelButtonText: this.trans('labels.cancel'),
    //     type: 'warning'
    //   })
    //   .then(() => {
    //     this.pull();
    //   })
    //   .catch(() => {});
    // },

    pull() {
      this.$refs.spinner.show();
      this.models.order.pullFromShopify({
          store_id: this.selectedStoreId,
          external_order_id: this.selectedOrder.id
        },
        (response) => {
          this.$refs.spinner.hide();
          this.selectedOrder = {};
          window.location.href = response.data.orderUrl;
        },
        () => {
          this.$refs.spinner.hide();
          this.selectedOrder = {};
        }
      );
    }
  }
};

</script>
