const
  { mapGetters } = require('vuex');

const
  loading = require('../../vendor/vue-loading'),
  loadAwesome = require('../../libraries/load-awesome');

Vue.component('add-product-wizard', {

  props: {
    productVariant: {
      type: Object,
      required: false,
      default: null
    },
    updateProduct: {
      type: Object,
      required: false,
      default: null
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    },
    showAddProductModal: {
      type: Boolean,
      required: false
    }
  },

  directives: {
    loading
  },

  components: {
    'add-product-wizard-templates': require('./add-product-wizard/templates'),
    'add-product-wizard-canvas': require('./add-product-wizard/canvas'),
    'add-product-wizard-pricing': require('./add-product-wizard/pricing')
  },

  vuex: {
    getters: {
      categories: (state) => {
        //return state.productCategories;
      },
      catalogAttributes: (state) => {
        return state.catalogAttributes;
      }
    }
  },

  data() {
    return {
      store: App.data.Store,
      selectedCategories: [],
      selectedModelTemplate: null,

      filteredGarment: {},
      selectedModels: [],
      selectedModelIds: [],
      selectedPrintFile: null,
      selectedPrintFileBack: null,
      selectedSourceFile: null,
      selectedSourceFileBack: null,

      showPricing: false,
      productTitle: null,
      productDescription: null,
      retailPrices: {},
      profit: 0,
      order: null,
      onPushSuccessCallback: null,

      loadingOptions: {
        text: loadAwesome.ballSpin
      },

      previewCanvasData: [],
      previewCanvasDataBack: [],
      selectedColors: [],
      printCoordinates: {},
      canvasSize: {},
      printCoordinatesBack: {},
      canvasSizeBack: {}
    }
      
  },

  computed: {

    leafCategory() {
      return this.selectedCategories[this.selectedCategories.length - 1];
    },

    selectedColorsUnique() {
      let addedColorIds = [];
      let colors = _.filter(this.selectedColors, (selectedColor) => {
        if (_.indexOf(addedColorIds, selectedColor.id) == -1) {
          addedColorIds.push(selectedColor.id);
          return true;
        }
        else {
          return false;
        }
      });

      return colors;
    },

    categoriesStepIsEnabled() {
      return Config.get('settings.public').product.wizard.CATEGORIES_STEP_IS_ENABLED;
    },

    isAllOverPrintOrSimilar() {
      return (
        this.selectedModelTemplate
        && this.selectedModelTemplate.garment
        && this.selectedModelTemplate.garment.isAllOverPrintOrSimilar
      );
    },

    allOverPrintsPreviewReplaceMode() {
      return Config.get('settings.public').product.wizard.ALL_OVER_PRINTS_PREVIEW_REPLACE;
    },

    categories() {
      return this.$store.state.productCategories;
    },

    catalogAttributes() {
      return this.$store.state.catalogAttributes;
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
    },
    selectedModelTemplate(selectedModelTemplate) {

      if (selectedModelTemplate) {
        Vue.nextTick(() => {
          this.productTitle = selectedModelTemplate.product_title;
          this.productDescription = selectedModelTemplate.product_description;
        });
      }
    },

    updateProduct(product, oldProduct) {
      if (
        product
        && oldProduct
        && product.id == oldProduct.id) {
        return;
      }

      this.setProduct(product);
    },

    showAddProductModal(showAddProductModal) {
      if (showAddProductModal == false) {
        this.updateProduct = null;
        this.showPricing = false;
        if (this.categoriesStepIsEnabled) {
          this.clearCategories();
        }
      }
    }
  },

  ready() {
    
    $('#js-attach-product-variant-modal').on('hidden.bs.modal', () => {
      this.clearSelectedCategories();

      if (
        typeof this.previewProductCanvasPrintImage != 'undefined'
        && this.previewProductCanvasPrintImage
      ) {
        window.previewProductCanvas
          .remove(this.previewProductCanvasPrintImage);
        this.previewProductCanvasPrintImage.remove();
      }

      if (
        typeof window.previewProductCanvasBaseImage != 'undefined'
        && window.previewProductCanvasBaseImage
      ) {
        window.previewProductCanvas
          .remove(window.previewProductCanvasBaseImage);
        window.previewProductCanvasBaseImage.remove();
      }

    });

    $('#js-attach-product-variant-modal').on('shown.bs.modal', () => {
      this.respondCanvas();
      if (
        typeof this.previewProductCanvasPrintImage != 'undefined'
        && this.previewProductCanvasPrintImage
      ) {
          this.previewProductCanvasPrintImage
          .center()
          .setCoords();
        }
    });
  },

  methods: {

    setProduct(product) {
      if (product) {
        this.updateProduct = product;

        if (product.id) {
          this.selectModelTemplate(product.template, () => {
            this.isLoading = true;
            this.productTitle = product.meta.title;
            this.productDescription = product.meta.body_html;

            this.$set('retailPrices', product.retailPrices);
            this.selectedModels = product.models;
            this.selectedModelIds = _.map(this.selectedModels, 'id');

            this.printCoordinates = product.canvas_meta ?
              product.canvas_meta.printCoordinates
              : {};
            this.canvasSize = product.canvas_meta
              ? product.canvas_meta.clientCanvasSize
              : {};
            this.printCoordinatesBack = product.canvas_meta ?
              product.canvas_meta.printCoordinatesBack
              : {};
            this.canvasSizeBack = product.canvas_meta
              ? product.canvas_meta.clientCanvasSizeBack
              : {};

            _.each(product.clientFiles, (clientFile) => {
              if (clientFile.design_location == App.constants.Models.ProductClientFile.LOCATION_BACK) {
                this.selectedPrintFileBack = clientFile.printFile;
                this.selectedSourceFileBack = clientFile.sourceFile;
              }
              else {
                this.selectedPrintFile = clientFile.printFile;
                this.selectedSourceFile = clientFile.sourceFile;
              }
            });

            this.isLoading = false;
          });
        }
      }
    },

    selectModelTemplate(template, callback) {
      this.selectedModelTemplate = null;

      if (!template || !template.id) {
        return;
      }

      this.isLoading = true;
      App.models.product.getProductModelTemplate(template.id, (response) => {
        this.selectedModelTemplate = response.data.template;

        Vue.nextTick(() => {
          this.isLoading = false;
          if (typeof callback != 'undefined') {
            callback();
          }
        });
      }, () => {
        this.isLoading = false;
      });
    },

    setProductVariant(productVariant) {
      if (productVariant) {
        this.productVariant = productVariant;

        if (productVariant.model) {
          this.selectCategory(productVariant.model.template.category);
          this.selectModelTemplate(productVariant.model.template, () => {
            this.selectedModels = [productVariant.model];
            this.selectedModelIds = [productVariant.model.id];
            this.selectFiles(productVariant.files);
          });
        }
      }
    },

    setOrder(order) {
      this.order = order;
    },

    clearCategories() {
      if (this.categoriesStepIsEnabled) {
        this.clearFilteredGarment();
        this.selectedCategories = [];
      }
    },

    clearFilteredGarment() {
      this.clearSelectedModelTemplate();
      this.filteredGarment = {};
    },

    clearSelectedModelTemplate() {
      this.selectedModelTemplate = null;
    },

    submit(e) {
      let
        form = $(e.target),
        formData = form.serialize();

      if (typeof this.loader == 'undefined') {
        this.loader = Ladda.create($("[type='submit']",form).get(0));
      }

      if (typeof this.loader == 'undefined' && this.loader) {
        this.loader.start();
      }

      // add new product
      if (!this.productVariant) {
        formData = formData + '&' + $.param({
          existing_file_id: (this.selectedPrintFile ? this.selectedPrintFile.id : null),
          existing_source_file_id: (this.selectedSourceFile ? this.selectedSourceFile.id : null),
          existing_file_back_id: (this.selectedPrintFileBack ? this.selectedPrintFileBack.id : null),
          existing_source_file_back_id: (this.selectedSourceFileBack ? this.selectedSourceFileBack.id : null),
          model_id: this.selectedModelIds,
          product_id: (this.updateProduct ? this.updateProduct.id : null)
        });
        App.models.product.sendOnModeration(formData, this.onPushSuccess, this.onFail);
      }
      else {
        formData = formData + '&' + $.param({'model_id': this.selectedModelIds[0]});
        if (this.order) {
          App.models.order.updateVariant(
            this.order.id,
            this.productVariant.id,
            formData,
            this.onPushSuccess,
            this.onFail
          );
        }
        else {
          App.models.productVariant.update(this.productVariant.id, formData, this.onPushSuccess, this.onFail);
        }
      }
    },

      setPushSuccessCallback(callback) {
        this.onPushSuccessCallback = callback;
      },

      onPushSuccess(response) {

        this.loader.stop();
        if (this.onPushSuccessCallback) {
          return this.onPushSuccessCallback(response);
        }
        else {
          window.location.reload();
        }
      },

      onFail() {
        this.loader.stop();
      }

  }
});
