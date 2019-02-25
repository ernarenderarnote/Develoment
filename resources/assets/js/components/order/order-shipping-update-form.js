const
  Money = require('js-money'),
  spinner = require('../vendor/vue-strap/Spinner.vue'),
  buttonGroup = require('vue-strap/dist/vue-strap.min').buttonGroup,
  radio = require('vue-strap/dist/vue-strap.min').radio;


Vue.component('order-shipping-update-form', {

  props: ['editable'],

  components: {
    radio,
    spinner,
    'button-group': buttonGroup
  },

  computed: {
    constants() {
      return App.constants;
    },
    subtotal() {

      let subtotal = new Money(0, Money.USD);
      if (!this.order) {
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
      if (this.order.shippingPrice && this.order.shippingPrice.amount) {
        return Money.fromInteger({
          amount: parseInt(this.order.shippingPrice.amount),
          currency: this.order.shippingPrice.currency
        });
      }
      else {
        return 0;
      }
    },
    total() {

      if (
        !this.order
        || !this.subtotal
        || typeof this.subtotal.multiply != 'function'
        || !this.shippingPrice
        || typeof this.shippingPrice.multiply != 'function'
      ) {
        return Money.fromInteger(0, Money.USD);
      }

      return this.subtotal.add(this.shippingPrice);
    },

    shippingPrices() {
      let shippingPrices = {};

      if (typeof this.shippingPricesMoney[this.constants.Models.Order.SHIPPING_METHOD_FIRST_CLASS] != 'undefined') {
        shippingPrices[this.constants.Models.Order.SHIPPING_METHOD_FIRST_CLASS] = Money.fromInteger({
          amount: parseInt(this.shippingPricesMoney[this.constants.Models.Order.SHIPPING_METHOD_FIRST_CLASS].amount),
          currency: this.shippingPricesMoney[this.constants.Models.Order.SHIPPING_METHOD_FIRST_CLASS].currency
        });

        shippingPrices[this.constants.Models.Order.SHIPPING_METHOD_PRIORITY_MAIL] = Money.fromInteger({
          amount: parseInt(this.shippingPricesMoney[this.constants.Models.Order.SHIPPING_METHOD_PRIORITY_MAIL].amount),
          currency: this.shippingPricesMoney[this.constants.Models.Order.SHIPPING_METHOD_PRIORITY_MAIL].currency
        });
      }

      return shippingPrices;
    }
  },

  data() {
    return {
      order: App.data.CurrentOrder,
      shippingPricesMoney: {},
      addressSameAsShipping: true,
      isOnReviewScreen: false
    }
  },

  watch: {
    'order.shipping_method': function(shippingMethod) {
      if (shippingMethod) {
        this.refreshShippingPrice();
      }
    }
  },

  ready() {
    this.setOrder(App.data.CurrentOrder);

    this.refreshShippingPrice();
  },

  methods: {
    setOrder(order) {
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

    refreshShippingPrice() {
      this.$refs.shippingPriceSpinner.show();
      App.models.order.getOrderWithNewShippingPrice({
        id: this.order.id,
        countryCode: this.order.shipping_meta.country_code,
        shippingMethod: this.order.shipping_method
      },
      (response) => {
        this.$refs.shippingPriceSpinner.hide();
        this.setOrder(response.data.order);
        this.shippingPricesMoney = response.data.shippingPrices;
      });
    }
  }
});
