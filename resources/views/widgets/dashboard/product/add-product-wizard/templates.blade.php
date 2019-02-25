<add-product-wizard-templates
    inline-template="true"
    :selected-categories.sync="selectedCategories"
    :leaf-category="leafCategory"
    :selected-model-template.sync="selectedModelTemplate"
    :show-add-product-modal="showAddProductModal"
    :filtered-garment.sync="filteredGarment">
    
    <div>
        <div v-if="categoryTemplates && !selectedModelTemplate">
        
            <div v-if="garmentGroups && filteredTemplates.length == 0">
                <div
                    v-for="garmentGroup in garmentGroups"
                    class="mt-20">
                    <div v-if="garmentGroup.garments && garmentGroup.garments.length > 0">
                        <div class="row product-garments-group">
                            <div class=" col-sm-3 col-md-3 col-lg-3 ">@{{ garmentGroup.name }}</div>
                            <div class=" col-sm-9 col-md-9 col-lg-9">
                                
                                <div
                                    v-for="garment in garmentGroup.garments"
                                    class=" col-sm-4 col-md-4 col-lg-4" style="float: left !important;display:inline-block;">
                                    <a
                                        href="#"
                                        @click.prevent="filterByGarment(garment)"
                                        class="thumbnail ta-c mxh-150">
                                        <img
                                            :src="garment.preview ? garment.preview : '{{ url('img/placeholders/placeholder-300x200.png') }}'"
                                            alt=""
                                            class="mxh-100" />
                                        <div class="caption">
                                            <div class="fw-b fz-14 text-muted">
                                                @{{ garment.name }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                    
                            </div>
                        </div>
                        <hr />
                    </div>
                </div>
            </div>
        
            <div
                v-if="filteredTemplates && filteredTemplates.length > 0"
                class="row col-md-12 js-templates-grid js-templates-grid-custom pt-20">
                <div v-for="template in filteredTemplates" class="col-xs-3 col-sm-3 col-md-3 select-tempalets-garment" >
                    <a href="#" class="thumbnail ta-c" @click.prevent="selectModelTemplate(template)">
                        <img
                            :src="template.preview ? template.preview : '{{ url('img/placeholders/placeholder-300x200.png') }}'"
                            alt=""
                            class="img-responsive" />
                        <div class="text-muted fw-b">@{{ template.name }}</div>
                        <div v-if="template.price > 0">$@{{ template.price }}</div>
                    </a>
                </div>
            </div>
                
        </div>
    </div>
</add-product-wizard-templates>
