Vue.component('order-review-update-form', {

  props: ['editable'],

  data() {
    return {
      order: App.data.CurrentOrder,
      addressSameAsShipping: false,
      isOnReviewScreen: true,
      billable: Spark.state.user,
      submitted: false
    }
  },

  computed: {
    cardIcon() {
      if (! this.billable.card_brand) {
        return 'fa-credit-card';
      }

      switch (this.billable.card_brand) {
        case 'American Express':
          return 'fa-cc-amex';
        case 'Diners Club':
          return 'fa-cc-diners-club';
        case 'Discover':
          return 'fa-cc-discover';
        case 'JCB':
          return 'fa-cc-jcb';
        case 'MasterCard':
          return 'fa-cc-mastercard';
        case 'Visa':
          return 'fa-cc-visa';
        default:
          return 'fa-credit-card';
      }
    }
  },

  ready() {
    var self = this;

    $(document).ready(() => {
      $('.js-address-same-as-shipping').on('ifChecked ifUnchecked', (e) => {
        self.addressSameAsShipping = !self.addressSameAsShipping;
      });
    });
  },

  methods: {}
});
