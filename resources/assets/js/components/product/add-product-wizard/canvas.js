const
  fabric = require('fabric').fabric,
  nouisliderComponent = require('vue-nouislider-component'),
  modal = require('vue-strap/dist/vue-strap.min').modal,
  tooltip = require('vue-strap/dist/vue-strap.min').tooltip,
  tabs = require('vue-strap/dist/vue-strap.min').tabset,
  tab = require('vue-strap/dist/vue-strap.min').tab,
  popper = require('vue-popper-component'),
  loading = require('../../../vendor/vue-loading'),
  loadAwesome = require('../../../libraries/load-awesome'),
  attributeOptionsTree = require('./attribute-options-tree');

const
  DEFAULT_DPI = Config.get('settings.public').product.wizard.DEFAULT_DPI,
  SCALE_RATIO = Config.get('settings.public').product.wizard.SCALE_RATIO,
  PRINT_AREA_WIDTH = Config.get('settings.public').product.wizard.PRINT_AREA_WIDTH,
  DPI_LOW_LIMIT = Config.get('settings.public').product.wizard.DPI_LOW_LIMIT,
  DPI_HIGH_LIMIT = Config.get('settings.public').product.wizard.DPI_HIGH_LIMIT,
  QUALITY_VALIDATION_LOW_LIMIT = Config.get('settings.public').product.wizard.QUALITY_VALIDATION_LOW_LIMIT;

module.exports = Vue.extend({
  name: 'add-product-wizard-canvas',

  props: {
    store: {
      type: Object,
      default: null
    },
    selectedCategories: {
      type: Array,
      default: () => []
    },
    leafCategory: {
      type: Object,
      default: null
    },
    selectedModelTemplate: {
      type: Object,
      default: null
    },
    previewCanvasData: {
      type: Array,
      default: () => []
    },
    previewCanvasDataBack: {
      type: Array,
      default: () => []
    },
    selectedColors: {
      type: Array,
      default: () => []
    },
    selectedColorsUnique: {
      type: Array,
      default: () => []
    },
    printCoordinates: {
      type: Object,
      default: () => {}
    },
    printCoordinatesBack: {
      type: Object,
      default: () => {}
    },
    canvasSize: {
      type: Object,
      default: () => {}
    },
    canvasSizeBack: {
      type: Object,
      default: () => {}
    },
    selectedPrintFile: {
      type: Object,
      default: () => {}
    },
    selectedPrintFileBack: {
      type: Object,
      default: () => {}
    },
    selectedSourceFile: {
      type: Object,
      default: () => {}
    },
    selectedSourceFileBack: {
      type: Object,
      default: () => {}
    },
    selectedModels: {
      type: Array,
      default: () => []
    },
    selectedModelIds: {
      type: Array,
      default: () => []
    },
    showPricing: {
      type: Boolean,
      default: false
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
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
    modal,
    tooltip,
    tabs,
    tab,
    attributeOptionsTree
  },

  directives: {
    loading
  },

  data() {
    return {
      activePreviewTab: 0,
      TAB_FRONT_PRINT: 0,
      TAB_BACK_PRINT: 1,
      isLoadingPreviewCanvas: false,
      isLoadingPreviewCanvasBack: false,
      loadingOptions: {
        text: loadAwesome.ballSpin
      },
      showPrintLibrary: false,
      showSourcesLibrary: false,
      selectedColorsValues: [],
      selectedSizesValues: [],
      previewDpi: 0,
      previewProductCanvasPrintImage: null,
      currentPreviewCanvasData: null,

      // back
        showPrintLibraryBack: false,
        showSourcesLibraryBack: false,
        previewDpiBack: 0,
        previewProductCanvasPrintImageBack: null,
        currentPreviewCanvasDataBack: null
    };
  },

  computed: {
    constants() {
      return App.constants;
    },

    colorOptions() {
      let
        colorOptions = [];

      // first option
      _.each(this.selectedModelTemplate.optionsTree, (firstLevelOption) => {

        if (firstLevelOption.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
          colorOptions.push(firstLevelOption);
        }
        else {
          // we have children
          if (
            !_.isEmpty(firstLevelOption.children)
            && _.isEmpty(colorOptions)
          ) {

            // second option
            _.each(firstLevelOption.children, (secondLevelOption) => {
              if (secondLevelOption.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
                colorOptions.push(secondLevelOption);
              }
            });
          }
        }

      });

      return colorOptions;
    },

    hasOnlyOneColorOption() {
      return (this.colorOptions && this.colorOptions.length == 1);
    },

    previewPrintImageQuality() {
      return this.previewDpi
        ? Math.round((this.previewDpi / (DEFAULT_DPI/2)) * 100)
        : 0;
    },

    previewPrintImageQualityBack() {
      return this.previewDpiBack
        ? Math.round((this.previewDpiBack / (DEFAULT_DPI/2)) * 100)
        : 0;
    },

    dpiSliderLowLimit() {
      return DPI_LOW_LIMIT;
    },

    dpiSliderHighLimit() {
      return DPI_HIGH_LIMIT;
    },

    qualityValidationLowLimit() {
      return QUALITY_VALIDATION_LOW_LIMIT;
    },

    positionControlsCouldBeShown() {
      return (
        this.selectedModelTemplate
        && this.selectedModelTemplate.image
        && (
          this.selectedPrintFile
          && this.selectedPrintFile.id
        )
      );
    },
    positionControlsCouldBeShownBack() {
      return (
        this.selectedModelTemplate
        && this.selectedModelTemplate.image
        && (
          this.selectedPrintFileBack
          && this.selectedPrintFileBack.id
        )
      );
    },
    backPrintEnabled() {
      return (
        (
          this.selectedModelTemplate
          && this.selectedModelTemplate.backPrintCanBeAddedOnItsOwn
        )
        || (
          this.selectedPrintFile
          && this.selectedPrintFile.id
        )
      );
    },
    pricingCouldBeShown() {
      return (
        this.selectedModelTemplate // template selected
        && (
          // models selected
          this.selectedModels.length >= 1

          // NOTE: shopify has limit for 100 variants per product
          // so we need to limit them here
          && (this.store.isInSync && this.selectedModels.length <= 100)
        )
        && !this.someSelectedModelsOutOfStock // all models in stock
        && ( // file selected
            (
              this.selectedModelTemplate.backPrintCanBeAddedOnItsOwn
              && (
                (
                  this.selectedPrintFile
                  && this.selectedPrintFile.id
                )
                || (
                  this.selectedPrintFileBack
                  && this.selectedPrintFileBack.id
                )
              )
            )
            || (
              !this.selectedModelTemplate.backPrintCanBeAddedOnItsOwn
              && (
                this.selectedPrintFile
                && this.selectedPrintFile.id
              )
            )
        )
        && (
          this.isAllOverPrintOrSimilar
          || this.previewPrintImageQuality >= this.qualityValidationLowLimit
        )
      );
    },

    someSelectedModelsOutOfStock() {
      let outOfStock = false;
      _.each(this.selectedModels, (model) => {
        if (model.inventory_status == this.constants.Models.ProductModel.INVENTORY_STATUS_OUT_OF_STOCK) {
          outOfStock = true;
        }
      });
      return outOfStock;
    }
  },

  watch: {
    activePreviewTab() {
      Vue.nextTick(() => {
        this.respondCanvas();
      });
    },

    selectedSizesValues(selectedSizesValues) {
      this.populateSelectedModelsByOptionValues();

      Vue.nextTick(() => {
        this.$dispatch('mntz:preview-color-selected');
      });
    },

    selectedColorsValues(selectedColorsValues) {
      this.populateSelectedModelsByOptionValues();

      Vue.nextTick(() => {
        this.$dispatch('mntz:preview-color-selected');
      });
    },

    selectedModelIds(selectedModelIds, oldSelectedModelIds) {

      if (selectedModelIds.toString() == oldSelectedModelIds.toString()) {
        return;
      }

      this.populateSelectedModelsByIds();

      if (!oldSelectedModelIds || oldSelectedModelIds.length == 0) {
        this.populateOptionValuesFromSelectedModels();
      }
    },

    previewDpi(value, oldValue) {
      if (!value || !this.previewProductCanvasPrintImage) {
        return;
      }

      if (value < DPI_LOW_LIMIT) {
        this.previewDpi = DPI_LOW_LIMIT;
        return;
      }
      else if (value > DPI_HIGH_LIMIT) {
        this.previewDpi = DPI_HIGH_LIMIT;
        return;
      }

      let scale = (DEFAULT_DPI / value) / SCALE_RATIO;

      this.previewProductCanvasPrintImage.set({
        scaleX: scale,
        scaleY: scale
      })
      .setCoords();

      window.previewProductCanvas
        .renderAll();

      this.savePreviewCanvasDataUrl();
    },

    previewDpiBack(value, oldValue) {
      if (!value || !this.previewProductCanvasPrintImageBack) {
        return;
      }

      if (value < DPI_LOW_LIMIT) {
        this.previewDpiBack = DPI_LOW_LIMIT;
        return;
      }
      else if (value > DPI_HIGH_LIMIT) {
        this.previewDpiBack = DPI_HIGH_LIMIT;
        return;
      }

      let scale = (DEFAULT_DPI / value) / SCALE_RATIO;

      this.previewProductCanvasPrintImageBack.set({
        scaleX: scale,
        scaleY: scale
      })
      .setCoords();

      window.previewProductCanvasBack
        .renderAll();

      this.savePreviewCanvasDataUrl();
    },

    selectedModelTemplate(selectedModelTemplate) {
      if (selectedModelTemplate) {
        Vue.nextTick(() => {
          this.initPreviewCanvas(() => {
            this.activePreviewTab = this.TAB_FRONT_PRINT;
          });

          // if we have only one color option
          // then preselect it and hide
          if (this.hasOnlyOneColorOption) {
            this.selectedColorsValues = [ this.colorOptions[0].value ];
            this.$dispatch('mntz:preview-color-selected', this.colorOptions[0]);
          }
        });
      }
      else {
        this.selectedColors = [];
        this.selectedColorsValues = [];
        this.selectedSizesValues = [];
        this.selectedModels = [];
        this.selectedModelIds = [];
        this.selectedPrintFile = null;
        this.selectedSourceFile = null;
        this.selectedPrintFileBack = null;
        this.selectedSourceFileBack = null;

        window.previewProductCanvas = null;
        this.previewProductCanvasPrintImage = null;

        window.previewProductCanvasBack = null;
        this.previewProductCanvasPrintImageBack = null;
      }
    },

    showPrintLibrary(show) {
      if (show) {
        this
          .$refs.printFilesLibrary
          .setOnChooseCallback((file) => {
            this.selectedPrintFile = file;
            this.showPrintLibrary = false;
            this.activePreviewTab = this.TAB_FRONT_PRINT;
          });
      }
    },

    showSourcesLibrary(show) {
      if (show) {
        this
          .$refs.sourceFilesLibrary
          .setOnChooseCallback((file) => {
            this.selectedSourceFile = file;
            this.showSourcesLibrary = false;
          });
      }
    },

    showPrintLibraryBack(show) {
      if (show) {
        this
          .$refs.printFilesLibraryBack
          .setOnChooseCallback((file) => {
            this.selectedPrintFileBack = file;
            this.showPrintLibraryBack = false;
            this.activePreviewTab = this.TAB_BACK_PRINT;
          });
      }
    },

    showSourcesLibraryBack(show) {
      if (show) {
        this
          .$refs.sourceFilesLibraryBack
          .setOnChooseCallback((file) => {
            this.selectedSourceFileBack = file;
            this.showSourcesLibraryBack = false;
          });
      }
    },

    selectedPrintFile(file) {
        Vue.nextTick(() => {
          this.initPreviewCanvas(() => {
            this.selectPrintFile(file);
          });
        });
    },

    selectedPrintFileBack(file) {

        Vue.nextTick(() => {
          this.initPreviewCanvas(() => {
            this.selectPrintFileBack(file);
          });
        });

    }
  },

  ready() {
    this.$on('mntz:preview-color-selected', (color) => {
      this.isLoading = true;
      if (window.previewProductCanvas) {

        let selectedColors = _.filter(this.colorOptions, (colorOption) => {
          return _.indexOf(this.selectedColorsValues, colorOption.value) != -1;
        });

        this.selectedColors = selectedColors;

        if (selectedColors.length > 0) {
          let colorExists = null;
          if (color) {
            colorExists = _.some(this.selectedColors, (selectedColor) => {
              return selectedColor == color.id
            });
          }

          if (colorExists) {
            this.selectCanvasPreviewBackgroundColor(color.value);
          }
          else {
            this.selectCanvasPreviewBackgroundColor(selectedColors[0].value);
          }
        }

        this.respondCanvas();
        this.savePreviewCanvasDataUrl();
      }

      this.isLoading = false;
    });

  },

  methods: {

    populateOptionValuesFromSelectedModels() {
      let
        selectedColorsValues = [],
        selectedSizesValues = [];

      _.each(this.selectedModels, (selectedModel) => {
        _.each(selectedModel.options, (option) => {
          if (option.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
            selectedColorsValues.push(option.value);
          }
          else {
            selectedSizesValues.push(option.value);
          }
        });
      });

      this.selectedColorsValues = selectedColorsValues;
      this.selectedSizesValues = selectedSizesValues;
    },

    populateSelectedModelsByIds() {
      let
        newSelectedModels = [],

        // trying to speed up _.contains() searching
        selectedModelIds = '|'+this.selectedModelIds.join('|')+'|',

        models = this.selectedModelTemplate && this.selectedModelTemplate.models
          ? this.selectedModelTemplate.models
          : [];

      if (models.length > 0) {
        for (let i = 0; i < models.length; i++) {
          if (selectedModelIds.indexOf('|'+models[i].id+'|') != -1) {
            newSelectedModels.push(models[i]);
          }
        }

        this.selectedModels = newSelectedModels;
      }
      else {
        this.selectedModels = [];
      }
    },

    /**
     * We will guess product model ids from the intersection of both options
     * if we have more than 1
     * This can be done with recursion as at was done in the view
     * but for now made in two cycles
     */
    populateSelectedModelsByOptionValues() {

      if (!this.selectedModelTemplate) {
        return;
      }

      let
        selectedModelIds = [];

      // first option
      _.each(this.selectedModelTemplate.optionsTree, (firstLevelOption) => {

        if (firstLevelOption.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
          if (_.indexOf(this.selectedColorsValues, firstLevelOption.value) != -1) {

            // if current level is the last
            if (_.isEmpty(firstLevelOption.children)) {
              selectedModelIds.push(firstLevelOption.model_id);
            }

            // we have children
            else {

              // second option
              _.each(firstLevelOption.children, (secondLevelOption) => {
                if (secondLevelOption.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
                  if (_.indexOf(this.selectedColorsValues, secondLevelOption.value) != -1) {
                    selectedModelIds.push(secondLevelOption.model_id);
                  }
                }
                else {
                  if (_.indexOf(this.selectedSizesValues, secondLevelOption.value) != -1) {
                    selectedModelIds.push(secondLevelOption.model_id);
                  }
                }
              });
            }

          }
        }
        else {
          if (_.indexOf(this.selectedSizesValues, firstLevelOption.value) != -1) {

            // if current level is the last
            if (_.isEmpty(firstLevelOption.children)) {
              selectedModelIds.push(firstLevelOption.model_id);
            }

            // we have children
            else {

              // second option
              _.each(firstLevelOption.children, (secondLevelOption) => {
                if (secondLevelOption.attribute.name.toLowerCase() == App.constants.Models.CatalogAttribute.ATTRIBUTE_COLOR) {
                  if (_.indexOf(this.selectedColorsValues, secondLevelOption.value) != -1) {
                    selectedModelIds.push(secondLevelOption.model_id);
                  }
                }
                else {
                  if (_.indexOf(this.selectedSizesValues, secondLevelOption.value) != -1) {
                    selectedModelIds.push(secondLevelOption.model_id);
                  }
                }
              });
            }

          }
        }

      });

      selectedModelIds = _.uniq(selectedModelIds);
      this.selectedModelIds = selectedModelIds;
    },

    selectPrintFile(file) {

      if (this.isAllOverPrintOrSimilar) {
        return;
      }

      if (!file || !file.id) {
        window.previewProductCanvas
          .remove(this.previewProductCanvasPrintImage);
        this.previewProductCanvasPrintImage.remove();

        this.savePreviewCanvasDataUrl();
        this.$dispatch('mntz:preview-color-selected');

        return;
      }

      this.isLoadingPreviewCanvas = true;
      Vue.nextTick(() => {

        fabric.Image.fromURL(file.url, (img) => {

          if (
            typeof this.previewProductCanvasPrintImage != 'undefined'
            && this.previewProductCanvasPrintImage
          ) {
            window.previewProductCanvas
              .remove(this.previewProductCanvasPrintImage);
            this.previewProductCanvasPrintImage.remove();
          }

          this.previewProductCanvasPrintImage = img;
          this.previewProductCanvasPrintImage.set({
            id: 'print',
            originX: 'center',
            originY: 'center',
            top: 0,
            left: 0,
            centeredScaling: true,
            hasRotatingPoint: false,
            uniScaleTransform: true
          });
          this.previewProductCanvasPrintImage.setControlsVisibility({
            mb: false,
            mt: false,
            ml: false,
            mr: false
          });

          this.previewProductCanvasPrintImage
            .set({
              scaleX: 1 / SCALE_RATIO,
              scaleY: 1 / SCALE_RATIO
            });

          window.previewProductCanvas
            .add(this.previewProductCanvasPrintImage)
            .setActiveObject(this.previewProductCanvasPrintImage);

          if (this.isAllOverPrintOrSimilar) {
            if (this.allOverPrintsPreviewReplaceMode) {
              window.previewProductCanvasBaseImage.remove();
            }
            else {
              this.previewProductCanvasPrintImage.sendToBack();
            }
          }
          else {
            this.previewProductCanvasPrintImage.bringToFront();
          }

          window.previewProductCanvas.renderAll();

          // scale first
          Vue.nextTick(() => {

            if (this.printCoordinates.top) {
              let scale = this.printCoordinates.width / this.previewProductCanvasPrintImage.width * SCALE_RATIO;

              let dpi = scale
                ? Math.round(DEFAULT_DPI / scale)
                : 0;

              if (dpi && this.previewDpi != dpi) {
                this.previewDpi = dpi;
              }
            }
            else {
              this.previewDpi = 150;
            }

            // then position
            Vue.nextTick(() => {

              if (this.printCoordinates.top) {
                this.previewProductCanvasPrintImage.set({
                  top: this.printCoordinates.top + (this.previewProductCanvasPrintImage.getBoundingRectHeight() / 2),
                  left: this.printCoordinates.left + (this.previewProductCanvasPrintImage.getBoundingRectWidth() / 2),
                })
                .setCoords();
              }
              else {
                this.previewProductCanvasPrintImage
                  .center()
                  .setCoords();
              }

              // and finally recalculate
              Vue.nextTick(() => {

                window.previewProductCanvas
                  .renderAll();

                this.savePreviewCanvasDataUrl();

                this.$dispatch('mntz:preview-color-selected');

                this.isLoadingPreviewCanvas = false;
              }); // Vue.nextTick
            }); // Vue.nextTick
          }); // Vue.nextTick
        }); // fabric.Image.fromURL
      }); // Vue.nextTick

    },

    // back
      selectPrintFileBack(file) {

        if (this.isAllOverPrintOrSimilar) {
          return;
        }

        if (!file || !file.id) {
          window.previewProductCanvasBack
            .remove(this.previewProductCanvasPrintImageBack);
          this.previewProductCanvasPrintImageBack.remove();

          this.savePreviewCanvasDataUrl();
          this.$dispatch('mntz:preview-color-selected');

          return;
        }

        this.isLoadingPreviewCanvasBack = true;
        Vue.nextTick(() => {

          fabric.Image.fromURL(file.url, (img) => {

            if (
              typeof this.previewProductCanvasPrintImageBack != 'undefined'
              && this.previewProductCanvasPrintImageBack
            ) {
              window.previewProductCanvasBack
                .remove(this.previewProductCanvasPrintImageBack);
              this.previewProductCanvasPrintImageBack.remove();
            }

            this.previewProductCanvasPrintImageBack = img;
            this.previewProductCanvasPrintImageBack.set({
              id: 'print-back',
              originX: 'center',
              originY: 'center',
              top: 0,
              left: 0,
              centeredScaling: true,
              hasRotatingPoint: false,
              uniScaleTransform: true
            });
            this.previewProductCanvasPrintImageBack.setControlsVisibility({
              mb: false,
              mt: false,
              ml: false,
              mr: false
            });

            this.previewProductCanvasPrintImageBack
              .set({
                scaleX: 1 / SCALE_RATIO,
                scaleY: 1 / SCALE_RATIO
              });

            window.previewProductCanvasBack
              .add(this.previewProductCanvasPrintImageBack)
              .setActiveObject(this.previewProductCanvasPrintImageBack);

            if (this.isAllOverPrintOrSimilar) {
              if (this.allOverPrintsPreviewReplaceMode) {
                window.previewProductCanvasBaseImageBack.remove();
              }
              else {
                this.previewProductCanvasPrintImageBack.sendToBack();
              }
            }
            else {
              this.previewProductCanvasPrintImageBack.bringToFront();
            }

            window.previewProductCanvasBack
              .renderAll();

            // scale first
            Vue.nextTick(() => {

              if (this.printCoordinatesBack.top) {
                let scale = this.printCoordinatesBack.width / this.previewProductCanvasPrintImageBack.width * SCALE_RATIO;

                let dpi = scale
                  ? Math.round(DEFAULT_DPI / scale)
                  : 0;

                if (dpi && this.previewDpiBack != dpi) {
                  this.previewDpiBack = dpi;
                }
              }
              else {
                this.previewDpiBack = 150;
              }

              // then position
              Vue.nextTick(() => {

                if (this.printCoordinatesBack.top) {
                  this.previewProductCanvasPrintImageBack.set({
                    top: this.printCoordinatesBack.top + (this.previewProductCanvasPrintImageBack.getBoundingRectHeight() / 2),
                    left: this.printCoordinatesBack.left + (this.previewProductCanvasPrintImageBack.getBoundingRectWidth() / 2),
                  })
                  .setCoords();
                }
                else {
                  this.previewProductCanvasPrintImageBack
                    .center()
                    .setCoords();
                }

                // and finally recalculate
                Vue.nextTick(() => {

                  window.previewProductCanvasBack
                    .renderAll();

                  this.savePreviewCanvasDataUrl();

                  this.$dispatch('mntz:preview-color-selected');

                  this.isLoadingPreviewCanvasBack = false;
                }); // Vue.nextTick
              }); // Vue.nextTick
            }); // Vue.nextTick
          }); // fabric.Image.fromURL
        }); // Vue.nextTick
      },

    /**************
     * canvas
     */

      savePreviewCanvasDataUrl() {

        if (this.isAllOverPrintOrSimilar) {
          return;
        }

        Vue.nextTick(() => {

          if (
            typeof window.previewProductCanvas == 'undefined'
            || !window.previewProductCanvas
          ) {
            return;
          }

          let
            activeObject = window.previewProductCanvas
              ? window.previewProductCanvas.getActiveObject()
              : null,
            activeObjectBack = window.previewProductCanvasBack ?
              window.previewProductCanvasBack.getActiveObject()
              : null,
            currentBackgroundColor = window.previewProductCanvas
              ? window.previewProductCanvas.backgroundColor
              : null;

          if (activeObject) {
            window.previewProductCanvas.discardActiveObject();
            if (window.previewProductCanvasBack) {
              window.previewProductCanvasBack.discardActiveObject();
            }
          }

          // save all colors previews
            let
              previewCanvasData = [],
              previewCanvasDataBack = [];
            _.each(this.selectedColors, (selectedColor) => {

              if (window.previewProductCanvas) {
                window.previewProductCanvas
                  .setBackgroundColor(selectedColor.value)
                  .renderAll();

                previewCanvasData[selectedColor.id] = window.previewProductCanvas.toDataURL({
                  format: 'jpeg',
                  quality: 0.2
                });
              }

              if (window.previewProductCanvasBack) {
                window.previewProductCanvasBack
                  .setBackgroundColor(selectedColor.value)
                  .renderAll();

                previewCanvasDataBack[selectedColor.id] = window.previewProductCanvasBack.toDataURL({
                  format: 'jpeg',
                  quality: 0.2
                });
              }
            });
            this.previewCanvasData = previewCanvasData;
            this.previewCanvasDataBack = previewCanvasDataBack;

          if (window.previewProductCanvas) {
            this.canvasSize = {
              'width': window.previewProductCanvas.getWidth(),
              'height': window.previewProductCanvas.getHeight()
            };
          }

          if (window.previewProductCanvasBack) {
            this.canvasSizeBack = {
              'width': window.previewProductCanvasBack.getWidth(),
              'height': window.previewProductCanvasBack.getHeight()
            };
          }

          if (this.previewProductCanvasPrintImage) {
            this.printCoordinates = this.previewProductCanvasPrintImage.getBoundingRect();

            let scale = this.previewProductCanvasPrintImage.scaleX * SCALE_RATIO;
            let dpi = scale
              ? Math.round(DEFAULT_DPI / scale)
              : 0;

            if (dpi && this.previewDpi != dpi) {
              this.previewDpi = dpi;
            }
          }

          if (this.previewProductCanvasPrintImageBack) {
            this.printCoordinatesBack = this.previewProductCanvasPrintImageBack.getBoundingRect();

            let scale = this.previewProductCanvasPrintImageBack.scaleX * SCALE_RATIO;
            let dpi = scale
              ? Math.round(DEFAULT_DPI / scale)
              : 0;

            if (dpi && this.previewDpiBack != dpi) {
              this.previewDpiBack = dpi;
            }
          }

          if (activeObject) {
            window.previewProductCanvas
              .setActiveObject(activeObject);
          }

          if (activeObjectBack) {
            window.previewProductCanvasBack
              .setActiveObject(activeObjectBack);
          }

          if (currentBackgroundColor) {
            if (window.previewProductCanvas) {
              window.previewProductCanvas
              .setBackgroundColor(currentBackgroundColor)
              .renderAll();
            }

            if (window.previewProductCanvasBack) {
              window.previewProductCanvasBack
              .setBackgroundColor(currentBackgroundColor)
              .renderAll();
            }
          }

        });
      },

      selectCanvasPreviewBackgroundColor(color) {
        this.currentPreviewCanvasData = color;

        if (window.previewProductCanvas) {
            window.previewProductCanvas
            .setBackgroundColor(color)
            .renderAll();
          }

        if (window.previewProductCanvasBack) {
            window.previewProductCanvasBack
            .setBackgroundColor(color)
            .renderAll();
          }
      },

      initPreviewCanvas(callback) {

        if (!this.selectedModelTemplate || this.isAllOverPrintOrSimilar) {
          return;
        }

        // front canvas
          let
            canvasInitialized = (
              typeof window.previewProductCanvas != 'undefined'
              && !!window.previewProductCanvas
            ),
            canvasBackInitialized = (
              typeof window.previewProductCanvasBack != 'undefined'
              && !!window.previewProductCanvasBack
            );

          if (!canvasInitialized) {
            if ($('#js-preview-product-canvas', this.$el).length) {
              window.previewProductCanvas = new fabric.Canvas('js-preview-product-canvas');
              window.previewProductCanvas.setBackgroundColor('rgb(255, 255, 255)');

              window.previewProductCanvas.on('object:modified', (event) => {
                this.limitPrintImageBounds(event.target);
                this.savePreviewCanvasDataUrl();
              });

              window.previewProductCanvas.on('object:moving', (event) => {
                this.limitPrintImageBounds(event.target);
              });
            }
          }

        // back canvas
          if (!canvasBackInitialized) {
            if ($('#js-preview-product-canvas-Back', this.$el).length) {
              window.previewProductCanvasBack = new fabric.Canvas('js-preview-product-canvas-Back');
              window.previewProductCanvasBack.setBackgroundColor('rgb(255, 255, 255)');

              window.previewProductCanvasBack.on('object:modified', (event) => {
                this.limitPrintImageBounds(event.target);
                this.savePreviewCanvasDataUrl();
              });

              window.previewProductCanvasBack.on('object:moving', (event) => {
                this.limitPrintImageBounds(event.target);
              });
            }
          }

        if (!canvasInitialized) {
            // front print
            if (
              typeof window.previewProductCanvas != 'undefined'
              && window.previewProductCanvas
            ) {
              this.isLoadingPreviewCanvas = true;
              let modelTemplateImage = this.selectedModelTemplate.image
                ? this.selectedModelTemplate.image
                : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // transparent gif

              fabric.Image.fromURL(modelTemplateImage, (img) => {
                this.isLoadingPreviewCanvas = false;
                window.previewProductCanvasBaseImage = img;
                window.previewProductCanvasBaseImage.set({
                  id: 'modelPreview',
                  alignX: 'left',
                  alignY: 'top',
                  selectable: false,
                  evented: false,
                  hasBorders: false,
                  hasControls: false,
                  hasRotatingPoint: false
                });

                window.previewProductCanvas
                  .add(window.previewProductCanvasBaseImage)
                  .moveTo(window.previewProductCanvasBaseImage, 10);
                window.previewProductCanvas.renderAll();

                this.respondCanvas();
                this.savePreviewCanvasDataUrl();

                // back print
                if (window.previewProductCanvasBack) {
                  this.isLoadingPreviewCanvasBack = true;
                  let modelTemplateImageBack = this.selectedModelTemplate.imageBack
                    ? this.selectedModelTemplate.imageBack
                    : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // transparent gif

                  fabric.Image.fromURL(modelTemplateImageBack, (img) => {
                    this.isLoadingPreviewCanvasBack = false;
                    window.previewProductCanvasBaseImageBack = img;
                    window.previewProductCanvasBaseImageBack.set({
                      id: 'modelPreviewBack',
                      alignX: 'left',
                      alignY: 'top',
                      selectable: false,
                      evented: false,
                      hasBorders: false,
                      hasControls: false,
                      hasRotatingPoint: false
                    });

                    window.previewProductCanvasBack
                      .add(window.previewProductCanvasBaseImageBack)
                      .moveTo(window.previewProductCanvasBaseImageBack, 10);
                    window.previewProductCanvasBack.renderAll();

                    this.respondCanvas();
                    this.savePreviewCanvasDataUrl();

                    if (typeof callback != 'undefined') {
                      callback();
                    }
                  });
                }
                else if (typeof callback != 'undefined') {
                  callback();
                }
              });
            }

          }
          else {
            if (typeof callback != 'undefined') {
              callback();
            }
          }
      },

      limitPrintImageBounds(el) {
        if (!this.isAllOverPrintOrSimilar) {
          // suppose el coords is center based
          el.left = el.left < el.getBoundingRectWidth() / 2
            ? el.getBoundingRectWidth() / 2
            : el.left;
          el.top = el.top < el.getBoundingRectHeight () / 2
            ? el.getBoundingRectHeight() / 2
            : el.top;

          let
            right = el.left + el.getBoundingRectWidth() / 2,
            bottom = el.top + el.getBoundingRectHeight() / 2;

          el.left = right > window.previewProductCanvas.width
            ? window.previewProductCanvas.width - el.getBoundingRectWidth() / 2
            : el.left;
          el.top = bottom > window.previewProductCanvas.height
            ? window.previewProductCanvas.height - el.getBoundingRectHeight() / 2
            : el.top;
        }
      },

      respondCanvas() {

        if (
          window.previewProductCanvas
          && typeof window.previewProductCanvasBaseImage != 'undefined'
        ) {

          let
            $parent = $('#js-preview-product-canvas')
              .parents('.js-preview-product-canvas-container'),
            parentWidth = $parent.width(),
            imageWidth = window.previewProductCanvasBaseImage.width,
            imageHeight = window.previewProductCanvasBaseImage.height,
            canvas_image_ratio = imageWidth/imageHeight;

            if (parentWidth) {
              if (imageWidth > parentWidth) {
                window.previewProductCanvasBaseImage
                  .setWidth(parentWidth)
                  .setHeight(parentWidth/canvas_image_ratio);
              }

              window.previewProductCanvas
                .setWidth(parentWidth)
                .setHeight(parentWidth/canvas_image_ratio)
                .renderAll();
            }
        }

        if (
          window.previewProductCanvasBack
          && typeof window.previewProductCanvasBaseImageBack != 'undefined'
        ) {

          let
            $parent = $('#js-preview-product-canvas')
              .parents('.js-preview-product-canvas-container'),
            parentWidth = $parent.width(),
            imageWidth = window.previewProductCanvasBaseImageBack.width,
            imageHeight = window.previewProductCanvasBaseImageBack.height,
            canvas_image_ratio = imageWidth/imageHeight;

            if (parentWidth) {
              if (imageWidth > parentWidth) {
                window.previewProductCanvasBaseImageBack
                  .setWidth(parentWidth)
                  .setHeight(parentWidth/canvas_image_ratio);
              }

              window.previewProductCanvasBack
                .setWidth(parentWidth)
                .setHeight(parentWidth/canvas_image_ratio)
                .renderAll();
            }
        }
      }
  }
});
