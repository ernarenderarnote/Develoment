const
  salvattore = require('salvattore');

module.exports = Vue.extend({
  name: 'add-product-wizard-templates',

  data() {
    return {
      filteredTemplates: []
    };
  },

  props: {
    selectedCategories: {
      type: Array,
      default: () => []
    },
    leafCategory: {
      type: Object,
      default: null
    },
    filteredGarment: {
      type: Object,
      default: null
    },
    selectedModelTemplate: {
      type: Object,
      default: null
    },
    showAddProductModal: {
      type: Boolean,
      required: false
    },
    isLoading: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  
  computed: {
    categoryTemplates() {
      return this.leafCategory
        ? this.leafCategory.templates
        : [];
    },

    /**
     * we will prepare to filter garments
     */
    garmentGroups() {
      let
        groups = {},
        garmentUnisexOther;

      // TODO: temporary show printio in Unisex / Other
        _.each(this.categoryTemplates, (template) => {
          let
            garmentGroup = template.garment.garmentGroup,
            garment = template.garment;

            if (garmentGroup.slug == 'unisex_men' && garment.slug == 'other') {
              garmentUnisexOther = garment;
            }
          });
      // TODO: ^^^

      _.each(this.categoryTemplates, (template) => {
        let
          garmentGroup = template.garment.garmentGroup,
          garment = template.garment;

        // TODO: temporary show printio in Unisex / Other
          if (
            garment.slug == 'printio'
            || garment.slug == 'headwear'
            || garment.slug == 'socks'
            || garment.slug == 'galloree'
          ) {
            garment = garmentUnisexOther;
          }
        // TODO: ^^^

        if (typeof groups[garmentGroup.id] == 'undefined') {
          groups[garmentGroup.id] = _.extend(Object.assign({}, garmentGroup), {
            garments: {}
          });
        }

        if (typeof groups[garmentGroup.id].garments[garment.id] == 'undefined') {
          groups[garmentGroup.id].garments[garment.id] = _.extend(
            Object.assign({}, garment), {
            garmentGroup: Object.assign({}, garmentGroup),
            templates: []
          });
        }

        groups[garmentGroup.id].garments[garment.id].templates.push(template);
      });

      groups = _.map(groups, (group) => {
        group.garments = _.map(group.garments, (garment) => {
          garment.templates = _.map(garment.templates, template => template);
          return garment;
        });
        group.garments = _.sortBy(group.garments, garment => garment.position);
        return group;
      });

      groups = _.sortBy(groups, garment => garment.position);

      return groups;
    },

    getGarmentsForGarmentGroup() {
      let garments = {};

      _.each(this.categoryTemplates, (template) => {

      });

      return garments;
    },
  },

  watch: {
    leafCategory(category) {
      this.clearSelectedProductModelTemplate();

      if (
        category
        && category.templates
        && category.templates.length == 1
      ) {
        this.selectModelTemplate(category.templates[0]);
      }

      this.reinitGrid();
    },
    selectedCategories(selectedCategories) {
      this.reinitGrid();
    },
    selectedModelTemplate(selectedModelTemplate) {
      this.reinitGrid();
    },
    showAddProductModal(isVisible) {
      if (isVisible) {
        setTimeout(() => {
          this.reinitGrid();
        }, 0);
      }
    },
    filteredGarment(filteredGarment) {
      if (!filteredGarment || !filteredGarment.id) {
        this.filteredTemplates = [];
      }

      this.reinitGrid();
    }
  },

  methods: {

    clearSelectedProductModelTemplate() {
      this.filteredTemplates = [];
      this.filteredGarment = null;
      this.selectModelTemplate(null);
    },

    initGrid() {
      let
        $grid = $('.js-templates-grid', this.$el),
        grid = $grid.get(0);
      if (
        grid
        && !$grid.data('grid-initialized')
        && $grid.is(':visible')
      ) {
        salvattore.registerGrid(grid);
        $grid.data('grid-initialized', 1);
      }
    },

    reinitGrid() {
      Vue.nextTick(() => {
        this.initGrid();

        Vue.nextTick(() => {
          let
            $grid = $('.js-templates-grid', this.$el),
            grid = $grid.get(0);
          if (grid && $grid.data('grid-initialized')) {
            salvattore.recreateColumns(grid);
          }
        });
      });
    },

    selectModelTemplate(template) {
      this.selectedModelTemplate = null;

      if (!template || !template.id) {
        return;
      }

      this.isLoading = true;
      setTimeout(() => {
        App.models.product.getProductModelTemplate(template.id, (response) => {
          this.isLoading = false;
          this.selectedModelTemplate = response.data.template;
        }, () => {
          this.isLoading = false;
        });
      }, 100);
    },

    filterByGarment(garment) {
      this.filteredGarment = garment;
      this.filteredTemplates = _.map(garment.templates, template => template);
    }
  }
});
