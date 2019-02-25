const
  money = require('money-math'),
  tooltip = require('vue-strap/dist/vue-strap.min').tooltip;

module.exports = Vue.extend({
  name: 'add-product-wizard-pricing',

  props: {
    productTitle: null,
    productDescription: null,
    selectedModelTemplate: {
      type: Object,
      default: null
    },
    selectedPrintFile: {
      type: Object,
      default: () => {}
    },
    selectedPrintFileBack: {
      type: Object,
      default: () => {}
    },
    previewCanvasData: {
      type: Array,
      default: () => []
    },
    selectedColorsUnique: {
      type: Array,
      default: () => []
    },
    retailPrices: {
      type: Object,
      default: () => []
    },
    selectedModels: {
      type: Array,
      default: () => []
    },
    profit: {

    },
    showPricing: {
      type: Boolean,
      default: false
    },
    updateProduct: {
      type: Object,
      required: false,
      default: null
    },
    isAllOverPrintOrSimilar: {
      type: Boolean,
      required: false,
      default: false
    },
    allOverPrintsPreviewReplaceMode: {
      type: Boolean,
      required: false,
      default: false
    }
  },

  components: {
    tooltip
  },

  data() {
    return {

    };
  },

  computed: {
    constants() {
      return App.constants;
    },
    money() {
      return money;
    },
    getRetailPrices() {
      let retailPrices = _.filter(_.toArray(this.retailPrices), (o) => typeof o != 'undefined');
      if (!_.isEmpty(retailPrices)) {
        let
          min = _.min(retailPrices),
          max = _.max(retailPrices);

        if (min == max) {
          return Lang.get('about') + ' $'+ money.floatToAmount(min);
        }
        else {
          return Lang.get('from')
            + ' $'+ money.floatToAmount(min)
            + ' ' + Lang.get('to')
            + ' $'+ money.floatToAmount(max);
        }
      }
      else {
        return '';
      }
    },
    retailProfit() {
      let retailProfit = [];

      if (
        this.selectedModelTemplate
        && _.size(this.selectedModels)
        && _.size(this.retailPrices)
      ) {
        _.each(this.selectedModels, (model) => {
          if (typeof this.retailPrices[model.id] != 'undefined') {
            let retailPrice = _.isNaN(parseFloat(this.retailPrices[model.id])) ? 0 : parseFloat(this.retailPrices[model.id]);

            retailProfit[model.id] = money.floatToAmount(
              retailPrice
              - (
                parseFloat(this.selectedModelTemplate.price)
                + parseFloat(this.modelsPrintPrices[model.id])
              )
            );
          }
        });
      }

      return retailProfit;
    },
    modelsPrintPrices() {
      let
        price = null,
        prices = {};

      _.each(this.selectedModels, (model) => {
        if (this.selectedPrintFile && this.selectedPrintFileBack) {
          price = parseFloat(model.bothSidesPrice);
        }
        else if (this.selectedPrintFile) {
          price = parseFloat(model.frontPrice);
        }
        else if (this.selectedPrintFileBack) {
          price = parseFloat(model.backPrice);
        }

        prices[model.id] = money.floatToAmount(price);
      });

      return prices;
    }
  },

  watch: {
    profit(val, oldVal) {
      if ( parseFloat(val) !== oldVal ) {
        this.profit = parseFloat(val);
      }

      if (
        !val
        || _.isNaN(parseFloat(val))
      ) {
        this.profit = 0;
      }
    },
    showPricing(show) {
      if (show) {
        Vue.nextTick(() => {
          this.setProfit(this.profit);
        });
      }
    }
  },

  methods: {

    setProfit(profit) {
      let
        retailPrices = {},
        modelPrice = 0;

      this.profit = parseFloat(this.profit);

      _.each(this.selectedModels, (model) => {
        if (
          typeof retailPrices[model.id] == 'undefined'
          || !retailPrices[model.id]
        ) {

          retailPrices[model.id] = money.add(
            money.floatToAmount(this.profit),
            money.floatToAmount(this.modelsPrintPrices[model.id])
          );
        }
      });

      this.profit = money.floatToAmount(parseFloat(this.profit));
      this.retailPrices = retailPrices;
    },

    validateRetailPrice(model_id) {
      Vue.nextTick(() => {
        if (
          !this.retailPrices[model_id]
          || _.isNaN(parseFloat(this.retailPrices[model_id]))
        ) {
          Vue.set(this.retailPrices, model_id, money.floatToAmount(0));
        }
        else {
          Vue.set(this.retailPrices, model_id, money.floatToAmount(
            parseFloat(
              this.retailPrices[model_id]
            )
          ));
        }
      });
    }
  }
});
