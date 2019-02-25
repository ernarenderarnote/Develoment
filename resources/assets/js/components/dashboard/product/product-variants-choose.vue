<script>
  
const
  Money = require('js-money'),
  tooltip = require('vue-strap/dist/vue-strap.min').tooltip;
  
let
  spinner = require('components/vendor/vue-strap/Spinner.vue');

module.exports = {
  name: 'product-variants-choose',
  
  props: {
    storeId: {
      type: Number,
      required: true
    },
    onSelect: {
      type: Function,
      required: true
    }
  },
  
  components: {
    tooltip,
    spinner
  },
  
  data() {
    return {
      products: {
        data: [],
        meta: {}
      },
      currentPage: 1,
      filters: {
        search: null
      },
      selectedProduct: {},
      selectedVariants: []
    };
  },
  
  computed: {
    constants() {
      return App.constants;
    }
  },
  
  ready() {
    this.loadPage(1);
  },
  
  methods: {
    
    selectProduct(selectedProduct) {
      selectedProduct = JSON.parse(JSON.stringify(selectedProduct));
      
      selectedProduct.variants.data = _.map(selectedProduct.variants.data, (variant) => {
        variant.printPriceMoney = Money.fromInteger({
          amount: parseInt(variant.printPriceMoney.amount),
          currency: variant.printPriceMoney.currency
        });
        
        return variant;
      });
      
      this.selectedProduct = selectedProduct;
    },
    
    search() {
      this.loadPage(1);
    },
    
    loadPage(page) {
      if (typeof page != 'undefined') {
        this.currentPage = page;
      }
      
      this.$refs.spinner.show();
      App.models.product.searchByStore({
        store_id: this.storeId,
        filters: this.filters,
        page: this.currentPage
      }, (response) => {
        this.$refs.spinner.hide();
        this.products = response.data.products;
      }, () => {
        this.$refs.spinner.hide();
      });
    },
    
    clearSelectedProduct() {
      this.selectedProduct = {};
      this.selectedVariants = [];
    },
    
    toggleSelectVariant(variant) {
      if (this.isSelectedVariant(variant)) {
        this.selectedVariants = _.filter(this.selectedVariants, (selected) => {
          return selected.id != variant.id;
        });
      }
      else {
        let selectedVariants = _.toArray(this.selectedVariants);
        selectedVariants.push(variant);
        this.selectedVariants = selectedVariants;
      }
    },
    
    isSelectedVariant(variant) {
      return !!_.size(_.filter(this.selectedVariants, { 'id': variant.id }));
    },
    
    addSelectedVariants() {
      this.onSelect(
        _.toArray(this.selectedVariants)
      );
      
      this.clearSelectedProduct();
    }
    
  }
};

</script>

<template>

<div class="pos-r">
  
  <ul class="breadcrumb" v-if="selectedProduct.id">
    <li>
      <a
        @click.prevent="clearSelectedProduct"
        href="#!">
        <i class="fa fa-angle-left"></i>
        {{ 'labels.select_product' | trans }}
      </a>
    </li>
  </ul>
  
  <div v-if="!selectedProduct.id" class="row">
    <div class="col-xs-12 mh-150">
      <spinner v-ref:spinner size="md"></spinner>
      
      <!-- search -->    
      <div class="row">
        <div class="col-xs-12 mb-30">
          <div class="input-group">
            <input
              type="search"
              class="form-control"
              v-model="filters.search"
              :placeholder="'labels.search_products' | trans"
              debounce="700"
              />
            <span class="input-group-btn">
              <button
                  @click.prevent="search()"
                  class="btn btn-primary"
                  type="button">
                  {{ 'actions.search' | trans }}
              </button>
            </span>
          </div>
        </div>
      </div>
          
      <!-- products list -->
      <div class="row">
        <div
          v-if="!products.data || products.data.length == 0"
          class="col-xs-12">
          <div class="alert alert-warning">
            <h3 class="ta-c mtb-20">{{ 'labels.you_have_no_products' | trans }}</h3>
          </div>
        </div>
        
        <div
          v-for="product in products.data"
          class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
          <div
            @click.prevent="selectProduct(product)"
            class="thumbnail cur-p">
            <div class="ta-c h-150">
              <div class="d-ib">
                <img
                  v-if="product && product.mockupPreview"
                  :src="product.mockupPreview.thumb"
                  alt=""
                  class="img-responsive mxh-150" />
                <img
                  v-else
                  src="/img/placeholders/placeholder-300x200.png"
                  alt=""
                  class="img-responsive mxh-150"
                  />
              </div>
            </div>
            <div class="caption">
              <tooltip placement="top" :content="product.name">
                <div class="ovt-e h-20">{{ product.name }}</div>
              </tooltip>
            </div>
          </div>
          
        </div>
      </div>
      
      <div class="row">
        <!-- pagination -->
        <div class="col-xs-12" v-if="products.meta.pagination && products.meta.pagination.total_pages > 1">
          <nav class="ta-c">
            <ul class="pagination">
              <li :class="{'disabled': currentPage == 1}">
                <a
                  href="#"
                  aria-label="Previous"
                  @click.prevent="loadPage(--currentPage)">
                    <i class="fa fa-angle-left"></i>
                </a>
              </li>
              <li
                v-for="page in products.meta.pagination.total_pages"
                :class="{'active': currentPage == page+1}">
                  <a href="#!" @click.prevent="loadPage(page+1)">
                    {{ page+1 }}
                  </a>
              </li>
              <li :class="{'disabled': currentPage == products.meta.pagination.total_pages}">
                <a
                  href="#"
                  aria-label="Next"
                  @click.prevent="loadPage(++currentPage)">
                  <i class="fa fa-angle-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
  
  <!-- step 2 -->
  <div v-if="selectedProduct.id" class="row">
    <div class="col-xs-12">
      
      <div class="list-group">
        <a
          v-for="variant in selectedProduct.variants.data"
          @click.prevent="toggleSelectVariant(variant)"
          href="#!"
          :class="'list-group-item '+(isSelectedVariant(variant) ? 'active' : '')">
          <span class="d-ib va-m" v-for="mockup in variant.mockups.data">
            <img
              class="h-50"
              :src="mockup.thumb"
              :alt="mockup.type"
              />
          </span>
          
          <span class="d-ib va-m">{{ variant.name }}</span>
          
          <span v-if="variant.model && variant.model.options" class="va-m">
            <span v-for="option in variant.model.options.data" class="label label-primary ml-5">
              <span
                v-if="option.attribute.name.toLowerCase() == constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                class="color-control d-ib w-10 h-10 p-0"
                :title="option.name + ' - ' + option.attribute.name"
                :style="'background-color: ' + option.value">
              </span>
              
              {{ option.name }}
              
              <small>
                {{ option.attribute.name }}
              </small>
            </span>
          </span>
          
          <span class="label label-default va-m ml-5">
            {{ variant.printPriceMoney | currency }}
          </span>
        </a>
      </div>
      
    </div>
    
    <div class="col-xs-12">
      <button
        @click="addSelectedVariants"
        class="btn btn-primary pull-right">
          {{ 'labels.add_selected_products' | trans }}
      </button>
    </div>
  </div>
  
</div>

</template>
