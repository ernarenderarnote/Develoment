<script>

const
  Money = require('js-money'),
  modal = require('vue-strap/dist/vue-strap.min').modal;

let
  spinner = require('components/vendor/vue-strap/Spinner.vue'),
  productVariantsChoose = require('components/dashboard/product/product-variants-choose.vue');

module.exports = {
  name: 'order-products-update-form',

  components: {
    modal,
    spinner,
    'product-variants-choose': productVariantsChoose
  },

  computed: {
    subtotal() {
      let subtotal = new Money(0, Money.USD);
      if (!this.order || typeof this.order.variants == 'undefined') {
        return subtotal;
      }

      _.each(this.order.variants, (variant) => {
        if (variant.printPriceMoney && typeof variant.printPriceMoney.multiply == 'function') {
          subtotal = subtotal.add(variant.printPriceMoney.multiply(variant.quantity), Money.USD);
        }
      });

      return subtotal;
    },

    shippingPrice() {
      return (this.order ? this.order.shippingPrice : Money.fromInteger(0, Money.USD));
    },

    total() {

      if (
        !this.order
        || !this.subtotal
        || typeof this.subtotal.multiply != 'function'
        || !this.order.shippingPrice
        || typeof this.order.shippingPrice.multiply != 'function'
      ) {
        return Money.fromInteger(0, Money.USD);
      }

      return this.subtotal.add(this.order.shippingPrice);
    },

    leafCategory() {
      return this.selectedCategories[this.selectedCategories.length - 1];
    },

    categoriesStepIsEnabled() {
      return Config.get('settings.public').product.wizard.CATEGORIES_STEP_IS_ENABLED;
    }
  },

  data() {
    return {
      order: {},
      selectedCategories: [],
      Money,
      currentVariant: {},
      showConfirmationModal: false,
      showAttachProductVariantWizardModal: false,
      addProductWizardLoading: false,
    }
  },

  watch: {
    categories(categories) {
      if (categories.length > 0 && !this.categoriesStepIsEnabled) {
        let selectedCategories = _.map(this.selectedCategories, category => category);
        selectedCategories.push(this.categories[0]);
        this.selectedCategories = selectedCategories;
      }
    },
    selectedCategories(selectedCategories) {
      this.selectedModelTemplate = null;
    }
  },

  ready() {
    this.setOrder(App.data.CurrentOrder);
  },

  methods: {

    setOrder(order) {
      order.shippingPrice = Money.fromInteger({
        amount: parseInt(order.shippingPrice.amount),
        currency: order.shippingPrice.currency
      });

      order.variants = _.each(order.variants, (variant) => {
        variant.printPriceMoney = Money.fromInteger({
          amount: parseInt(variant.printPriceMoney.amount),
          currency: variant.printPriceMoney.currency
        });

        variant.customerPaidPriceMoney = Money.fromInteger({
          amount: parseInt(variant.customerPaidPriceMoney.amount),
          currency: variant.customerPaidPriceMoney.currency
        });

        return variant;
      });

      Vue.nextTick(() => {
        this.order = order;
      });
    },


    // add products to order
    //addProduct() {
    //  console.log('addProduct', this.$refs);
    //  this.addProductWizardLoading = true;
    //  App.models.order.addVariant(this.order.id, (response) => {
    //    this.addProductWizardLoading = false;
    //
    //    let addProductWizard = this.$refs.addProductWizard;
    //
    //      console.log('addProduct', addProductWizard);
    //
    //    addProductWizard.setProductVariant(response.data.variant);
    //    addProductWizard.setOrder(response.data.order);
    //
    //    this.setOrder(response.data.order);
    //
    //    this.showAddProductModal = true;
    //  }, () => {
    //
    //  });
    //},

    //editProduct(variant_id) {
    //  console.log('editProduct', variant_id);
    //  let
    //    self = this,
    //    variant = _.find(self.order.variants, (variant) => {
    //      return variant.id == variant_id;
    //    }),
    //    addProductWizard = self
    //      .$refs.attachProductVariantModal
    //      .$refs.addProductWizard;
    //
    //  addProductWizard.setProductVariant(variant);
    //  addProductWizard.setOrder(self.order);
    //  addProductWizard.setPushSuccessCallback((response) => {
    //    self.setOrder(response.data.order);
    //    $('#js-attach-product-variant-modal', this.$root.el).modal('hide');
    //  });
    //
    //  $('#js-attach-product-variant-modal', this.$root.el).modal('show');
    //},

    updateProduct(variant_id) {
      let
        self = this,
        variant = _.find(this.order.variants, (variant) => {
          return variant.id == variant_id;
        });

      App.models.order.updateVariantPrices(this.order.id, variant_id, {
        'name': variant.name,
        'quantity': variant.quantity,
        'retail_price': variant.customerPaidPriceMoney.toString()
      }, (response) => {
        self.setOrder(response.data.order);
      }, () => {});
    },

    //copyProduct(variant_id) {
    //  var variant = _.find(this.order.variants, (variant) => {
    //    return variant.id == variant_id;
    //  });
    //
    //  App.models.order.copyVariant(this.order.id, variant_id, (response) => {
    //    this.setOrder(response.data.order);
    //  }, () => {});
    //},

    attachVariants(variant_ids) {
      this.$refs.spinner.show();
      App.models.order.attachVariants({
        id: this.order.id,
        variant_ids
      },
      (response) => {
        this.$refs.spinner.hide();
        this.setOrder(response.data.order);
      },
      () => {
        this.$refs.spinner.hide();
      });
    },

    showDetachConfirmation(variant) {
      this.showConfirmationModal = true;
      this.currentVariant = variant;
    },

    detachCurrentVariant() {
      this.$refs.spinner.show();
      App.models.order.detachVariant({
        id: this.order.id, variant_id: this.currentVariant.id
      },
      (response) => {
        this.$refs.spinner.hide();
        this.setOrder(response.data.order);
        this.showConfirmationModal = false;
      },
      () => {
        this.$refs.spinner.hide();
      });
    },

    onVariantsSelect(selectedVariants) {
      this.attachVariants(
        _.pluck(selectedVariants, 'id')
      );

      this.showAttachProductVariantWizardModal = false;
    }

  }
};

</script>

<template>

<div class="pos-r">
  <spinner v-ref:spinner size="md"></spinner>

  <table class="table" v-if="order">
    <thead>
      <tr>
        <th>{{ 'labels.product' | trans }}</th>
        <th>&nbsp;</th>
        <th>{{ 'labels.print_file' | trans }}</th>
        <th>{{ 'labels.size_qty' | trans }}</th>
        <th>{{ 'labels.price' | trans }}</th>
        <th>{{ 'labels.retail' | trans }}</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="variant in order.variants">
        <td class="product-image">
          <img
            :src="variant.model && variant.model.template && variant.model.template.preview ? variant.model.template.preview : '/img/placeholders/placeholder-300x200.png'"
            alt=""
            class="img-responsive" />
        </td>
        <td width="230">
          <div class="details">
            <div class="mb-15">
              {{ variant.name }}
            </div>
            <div class="item-details">
              <div class="product-detail-widget" v-if="variant.model">
                <div>
                  <b>{{ 'labels.model' | trans }}:</b>
                  {{ variant.model.template.name }}
                </div>

                <div v-for="option in variant.model.options">
                  {{ option.name }}
                  <small class="text-muted">
                    {{ option.attribute.name }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </td>
        <td width="288">
          <div v-for="file in variant.files" class="thumbnail ta-c d-ib">
            <div>
              <img :src="file.url" alt="" class="mxw-100 img-responsive" />
            </div>
            <div>{{ 'labels.'+file.type | trans }}</div>
          </div>
          <div v-for="file in variant.mockups" class="thumbnail ta-c d-ib">
            <div>
              <img :src="file.url" alt="" class="mxw-100 img-responsive" />
            </div>
            <div>{{ 'labels.'+file.type | trans }}</div>
          </div>
        </td>
        <td class="w-100">
          <input
            type="text"
            name="qty"
            v-model="variant.quantity"
            debounce="500"
            @change="updateProduct(variant.id)"
            class="d-ib form-control w-70"
            />
        </td>
        <td class="price">
          <span class="d-ib va-m h-35">
            {{ variant.printPriceMoney | currency }}
          </span>
        </td>
        <td class="w-100">
          <span class="d-ib">$</span>
          <input
            type="text"
            v-model="variant.customerPaidPriceMoney"
            :placeholder="variant.customerPaidPriceMoney"
            debounce="500"
            @change="updateProduct(variant.id)"
            class="d-ib form-control w-70"
            />
        </td>
        <td>
          <button
              type="button"
              class="ta-c text-muted btn btn-link"
              @click="showDetachConfirmation(variant)">
                  <i class="fa fa-trash d-b"></i>
                  {{ 'actions.delete' | trans }}...
          </button>
        </td>
      </tr>

      <tr v-if="order && typeof order.variants != 'undefined' && order.variants.length < 1">
        <td colspan="7">
          <div class="alert alert-warning">
            {{ 'labels.add_products_to_continue' | trans }}
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="panel">
    <button
        type="button"
        class="btn btn-primary"
        @click="showAttachProductVariantWizardModal = true">
        {{ 'actions.add_product' | trans }}
    </button>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="col-md-6 col-md-offset-3">
        <table class="table mt-15">
          <tbody>
            <tr>
              <td class="fw-b tt-u">{{ 'labels.subtotal' | trans }}</td>
              <td class="ta-nd">
                {{ subtotal | currency }}
              </td>
            </tr>
            <tr>
              <td class="fw-b tt-u">{{ 'labels.shipping_&_handling' | trans }}</td>
              <td class="ta-nd">
                {{ shippingPrice | currency }}
              </td>
            </tr>
            <tr>
              <td class="fw-b tt-u">{{ 'labels.total' | trans }}</td>
              <td class="ta-nd">
                {{ total | currency }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-xs-12 ta-c">
      <a
        :href="'/dashboard/orders/'+order.id+'/shipping'"
        class="btn btn-danger"
        :disabled="order && typeof order.variants != 'undefined' && order.variants.length < 1">
        {{ 'actions.continue_to_shipping' | trans }}
      </a>
    </div>
  </div>

  <modal
    :show.sync="showConfirmationModal"
    :title="'labels.delete_product' | trans"
    small="true"
    backdrop="true"
    :ok-text="'labels.delete' | trans"
    :callback="detachCurrentVariant">
    <div slot="modal-body" class="d-n"></div>
  </modal>

  <modal
    :show.sync="showAttachProductVariantWizardModal"
    :title="'labels.add_product' | trans"
    large="true"
    backdrop="true">
    <div slot="modal-body" class="modal-body">

      <div v-if="showAttachProductVariantWizardModal && order && order.store">
        <product-variants-choose
          :store-id="order.store.id"
          :on-select="onVariantsSelect"></product-variants-choose>
      </div>

    </div>
    <div slot="modal-footer" class="modal-footer d-n"></div>
  </modal>

</div>

</template>
