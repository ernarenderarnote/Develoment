<script>

const
  salvattore = require('salvattore');

module.exports = {
  name: 'add-product-wizard-categories',
  
  props: {
    categories: {
      type: Array,
      default: () => []
    },
    selectedCategories: {
      type: Array,
      default: () => []
    },
    showAddProductModal: {
      type: Boolean,
      required: false
    },
    selectedModelTemplate: {
      type: Object,
      default: null
    }
  },
  
  data() {
    return {
      currentLevelCategories: []
    };
  },
  
  computed: {
    categoriesStepIsEnabled() {
      return Config.get('settings.public').product.wizard.CATEGORIES_STEP_IS_ENABLED;
    }
  },
  
  watch: {
    currentLevelCategories() {
      this.reinitGrid();
    },
    categories() {
      this.clearSelectedCategories();
    },
    selectedCategories(selectedCategories, oldSelectedCategories) {
      if (
        selectedCategories.length == 0
        && selectedCategories.length != oldSelectedCategories.length
      ) {
        this.clearSelectedCategories();
      }
      this.reinitGrid();
    },
    showAddProductModal(isVisible) {
      if (isVisible) {
        setTimeout(() => {
          this.clearSelectedCategories();
        }, 0);
      }
    }
  },
  
  methods: {
    clearSelectedCategories() {
      if (this.categoriesStepIsEnabled) {
        this.selectedCategories = [];
        this.currentLevelCategories = this.categories;
      }
    },
    
    initGrid() {
      let
        $grid = $('.js-current-level-categories-grid', this.$el),
        grid = $grid.get(0);
        
      if (
        !!grid
        && !$grid.data('grid-initialized')
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
            $grid = $('.js-current-level-categories-grid', this.$el),
            grid = $grid.get(0);
            
          if (grid && $grid.data('grid-initialized')) {
            salvattore.recreateColumns(grid);
          }
        });
      });
    },
    
    selectCategory(category) {
      this.selectedCategories.push(category);
      this.currentLevelCategories = category.children;
    }
  }
};

</script>

<template>
  
<div>
  
  <div
    class="row grid-4-columns js-current-level-categories-grid pt-20">
    <div >
      <a href="#" class="thumbnail ta-c" @click="selectCategory(category)">
        <img
          :src="category.preview ? category.preview : '/img/placeholders/placeholder-300x200.png'"
          alt=""
          class="img-responsive" />
        <div>{{ category.name }}</div>
      </a>
    </div>
  </div>
</div>

</template>
