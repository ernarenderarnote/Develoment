<div class="add-product-wizard">
    
    <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-12 p-0">
        
            <ul class="breadcrumb" v-if="!showPricing && !updateProduct">
                <li>
                    <a
                        v-if="categoriesStepIsEnabled"
                        href="#" @click.prevent="clearCategories">
                        @lang('actions.all_categories')/
                    </a>
                    <a
                        v-if="!categoriesStepIsEnabled"
                        href="#" @click.prevent="clearFilteredGarment">
                        @lang('actions.all_categories')/
                    </a>
                </li>
                <li
                    v-if="categoriesStepIsEnabled && selectedCategories && selectedCategories.length"
                    v-for="category in selectedCategories">
                    <a href="#" @click.prevent="clearFilteredGarment">
                        @{{ category.name }}
                    </a>
                </li>
                <li v-if="filteredGarment && filteredGarment.id">
                    <a href="#" @click.prevent="clearSelectedModelTemplate">
                        @{{ filteredGarment.garmentGroup.name }} @{{ filteredGarment.name }}
                    </a>
                </li>
                <li v-if="selectedModelTemplate && selectedModelTemplate.id">
                    @{{ selectedModelTemplate.product_title }}
                </li>
            </ul>
            <ul v-if="showPricing" class="breadcrumb">
                <li>
                    <a href="#" @click.prevent="showPricing = false">
                        <i class="fa fa-angle-left"></i>
                        @lang('actions.back_to_product') -
                        @{{ selectedModelTemplate.product_title }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
   
    <div class="row">

        <form class="col-sx-12 col-sm-12 col-md-12 col-lg-12" 
            @submit.prevent="submit($event)"
            v-loading="isLoading"
            :loading-options="loadingOptions">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="store_id" value="{{ $store->id }}" />
            <input v-if="selectedModelTemplate" type="hidden" name="product_model_template_id" :value="selectedModelTemplate.id" />
            <input v-if="productVariant" type="hidden" name="product_variant_id" :value="productVariant.id" />
            <input v-if="printCoordinates" type="hidden" name="print_coordinates" :value="printCoordinates | json" />
            <input v-if="canvasSize" type="hidden" name="canvas_size" :value="canvasSize | json" />
            <input v-if="printCoordinatesBack" type="hidden" name="print_coordinates_back" :value="printCoordinatesBack | json" />
            <input v-if="canvasSizeBack" type="hidden" name="canvas_size_back" :value="canvasSizeBack | json" />
            
            <div v-if="!updateProduct">
                <!-- step 1: categories -->
                <add-product-wizard-categories
                    :categories="categories"
                    :selected-categories.sync="selectedCategories"
                    :selected-model-template.sync="selectedModelTemplate"
                    :show-add-product-modal="showAddProductModal">
                </add-product-wizard-categories>
                    
                <!-- step 2: models -->
                @include('widgets.dashboard.product.add-product-wizard.templates')
            </div>
                
            <!-- step 3: model selected -->
            @include('widgets.dashboard.product.add-product-wizard.canvas')
            
            <!-- step 4: pricing -->    
            @include('widgets.dashboard.product.add-product-wizard.pricing')
        </form>
    </div>
</div>
