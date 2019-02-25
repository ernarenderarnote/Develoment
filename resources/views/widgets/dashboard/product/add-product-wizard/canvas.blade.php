<add-product-wizard-canvas
    inline-template="true"
    :selected-categories="selectedCategories"
    :leaf-category="leafCategory"
    :store="store"
    :selected-model-template="selectedModelTemplate"
    :print-coordinates.sync="printCoordinates"
    :canvas-size.sync="canvasSize"
    :print-coordinates-back.sync="printCoordinatesBack"
    :canvas-size-back.sync="canvasSizeBack"
    :selected-models.sync="selectedModels"
    :selected-model-ids.sync="selectedModelIds"
    :selected-print-file.sync="selectedPrintFile"
    :selected-print-file-back.sync="selectedPrintFileBack"
    :selected-source-file.sync="selectedSourceFile"
    :selected-source-file-back.sync="selectedSourceFileBack"
    :is-loading.sync="isLoading"
    :preview-canvas-data.sync="previewCanvasData"
    :preview-canvas-data-back.sync="previewCanvasDataBack"
    :selected-colors.sync="selectedColors"
    :selected-colors-unique="selectedColorsUnique"
    :show-pricing.sync="showPricing"
    :is-all-over-print-or-similar="isAllOverPrintOrSimilar"
    :all-over-prints-preview-replace-mode="allOverPrintsPreviewReplaceMode">

    <div class="col-xs-12 col-md-12 col-sm-12">
        <div class="row" :class="{'d-none': (!selectedModelTemplate || showPricing)}">

            <div
                class="col-xs-12 col-md-12 col-sm-12">
                <h5
                    v-if="selectedModelTemplate"
                    class="fz-22 fw-b text-left">
                    @{{ selectedModelTemplate.product_title }}
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fz-16 fw-b text-left">@lang('actions.choose_your_graphic'):</h6>
                        <div class="container">
                            <div class="row mb-30">
                                @include('widgets.dashboard.product.add-product-wizard.choose_graphic')
                            </div>
                        </div>
                        <div
                            class="mb-30"
                            v-if="selectedModelTemplate && selectedModelTemplate.catalogAttributes.length > 0">

                            <div class="attribute-options-tree">
                                <attribute-options-tree
                                    :selected-model-ids.sync="selectedModelIds"
                                    :selected-colors-values.sync="selectedColorsValues"
                                    :selected-sizes-values.sync="selectedSizesValues"
                                    :options="selectedModelTemplate.optionsTree"
                                    :first-level="true"
                                    :multiple="true"
                                    :option-prefix="''"
                                    :has-only-one-color-option="hasOnlyOneColorOption"
                                    />
                            </div>

                            <div v-if="selectedModels.length > 100 && store.isInSync" class="text-danger">
                                @{{ lang('labels.shopify_variants_limit_message') }}
                            </div>

                            <div v-for="model in selectedModels">
                                <div v-if="model.inventory_status == constants.Models.ProductModel.INVENTORY_STATUS_OUT_OF_STOCK">
                                    <div class="d-ib mr-5" v-for="option in model.options">
                                        @{{ option.name }}
                                        <small class="text-muted">
                                            @{{ option.attribute.name }}
                                        </small>
                                    </div>
                                    <div class="d-ib text-danger">@{{ lang('labels.out_of_stock') }}</div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-6">
                        <tabs
                            v-if="selectedModelTemplate"
                            :active.sync="activePreviewTab">
                            <tab
                                v-if="selectedModelTemplate.image || isAllOverPrintOrSimilar"
                                header="@lang('labels.front_print')">
                                @include('widgets.dashboard.product.add-product-wizard.canvas-previews', [
                                    'suffix' => ''
                                ])
                            </tab>
                            <tab
                                v-if="selectedModelTemplate.imageBack"
                                header="@lang('labels.back_print')"
                                :disabled="!backPrintEnabled">
                                @include('widgets.dashboard.product.add-product-wizard.canvas-previews', [
                                    'suffix' => 'Back'
                                ])
                            </tab>
                        </tabs>
                    </div>
                </div>

                <div class="footer-block text-left">
                    <button
                        type="button"
                        :class="'btn ' + (pricingCouldBeShown ? 'btn-success' : 'btn-warning')"
                        @click="showPricing = true"
                        :disabled="!pricingCouldBeShown">
                            @lang('actions.proceed_to_pricing')
                    </button>
                </div>
            </div>
        </div>

        <!-- library modal -->
        <modal
            :show.sync="showPrintLibrary"
            large="true"
            effect="zoom"
            backdrop="false"
            header="@lang('labels.files_library')">
            <div slot="modal-body" class="modal-body">
                <div v-if="showPrintLibrary">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'print-files-library'
                    ])
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer d-n"></div>
        </modal>

        <!-- sources library modal -->
        <modal
            :show.sync="showSourcesLibrary"
            large="true"
            effect="zoom"
            backdrop="false"
            header="@lang('labels.source_files_library')">
            <div slot="modal-body" class="modal-body">
                <div v-if="showSourcesLibrary">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'source-files-library'
                    ])
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer d-n"></div>
        </modal>

        <!-- back library modal -->
        <modal
            :show.sync="showPrintLibraryBack"
            large="true"
            effect="zoom"
            backdrop="false"
            header="@lang('labels.files_library')">
            <div slot="modal-body" class="modal-body">
                <div v-if="showPrintLibraryBack">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'print-files-library-back'
                    ])
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer d-n"></div>
        </modal>

        <!-- back sources library modal -->
        <modal
            :show.sync="showSourcesLibraryBack"
            large="true"
            effect="zoom"
            backdrop="false"
            header="@lang('labels.source_files_library')">
            <div slot="modal-body" class="modal-body">
                <div v-if="showSourcesLibraryBack">
                    @include('widgets.dashboard.library.library-grid', [
                        'tagName' => 'source-files-library-back'
                    ])
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer d-n"></div>
        </modal>
    </div>

</add-product-wizard-canvas>


{{-- the template below is more universal than the current one --}}
{{-- it will be useful when different sizes have different colors --}}
{{-- or when we will have options tree with more than 2 options --}}
{{--@section('footer')
<template id="js-attribute-options-tree-tmpl">

    <ul class="lis-n pl-10 d-b">
        <li
            v-for="option in options"
            :class="'mr-5 ' + (firstLevel ? 'd-b' : 'd-ib')">
            <label
                v-if="option.attribute.name.toLowerCase() == constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                class="fw-n m-0"
                :for="optionPrefix + '_o' + option.id">

                <span
                    :class="'color-control d-ib w-20 h-20 p-0 ' + (_.indexOf(selectedModelIds, option.model_id) != -1 || selectedModelIds == option.model_id ? 'active fa fa-check' : 'not-active')"
                    :title="option.name + ' - ' + option.attribute.name"
                    :style="'background-color: ' + option.value">
                </span>

                <input
                    v-if="option && _.isEmpty(option.children)"
                    :id="optionPrefix + '_o' + option.id"
                    class="d-none"
                    :type="multiple ? 'checkbox' : 'radio'"
                    :value="option.model_id"
                    v-model="selectedModelIds"
                    @change="$dispatch('mntz:preview-color-selected', option)"
                    />

            </label>
            <label
                v-if="option.attribute.name.toLowerCase() != constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                class="fw-n m-0"
                :for="optionPrefix + '_o' + option.id">

                <input
                    v-if="option && _.isEmpty(option.children)"
                    :id="optionPrefix + '_o' + option.id"
                    class="d-none"
                    :type="multiple ? 'checkbox' : 'radio'"
                    :value="option.model_id"
                    v-model="selectedModelIds"
                    />

                @{{ option.name }} <small class="text-muted">@{{ option.attribute.name }}</small>
            </label>

            <attribute-options-tree
                v-if="option && option.children"
                :options="option.children"
                :first-level="false"
                :multiple="multiple"
                :selected-model-ids.sync="selectedModelIds"
                :option-prefix="'_o' + option.id"
                ></attribute-options-tree>

        </li>
    </ul>
</template>
@append--}}


@section('footer')
<template id="js-attribute-options-tree-tmpl">

    <attribute-options-tree
        v-if="first(options) && first(options).children"
        :options="first(options).children"
        :first-level="false"
        :multiple="multiple"
        :selected-model-ids.sync="selectedModelIds"
        :selected-colors-values.sync="selectedColorsValues"
        :selected-sizes-values.sync="selectedSizesValues"
        :option-prefix="'_o' + first(options).id"
        :has-only-one-color-option="hasOnlyOneColorOption"
        ></attribute-options-tree>
       
    <div v-if="!_.isEmpty(options) && (first(options).attribute.name.toLowerCase() != constants.Models.CatalogAttribute.ATTRIBUTE_COLOR || !hasOnlyOneColorOption)">
        <h6 class="fz-16 fw-b text-left mt-5">
            @lang('labels.select') @{{ first(options).attribute.name }}(s):
        </h6>
        <ul class="lis-n pl-0 d-b">
            <li
                v-for="option in options"
                class="mr-2 d-ib">

                <label
                    v-if="option.attribute.name.toLowerCase() == constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                    class="m-0"
                    :for="optionPrefix + '_o' + option.id">

                    <span
                        :class="'color-control d-ib w-20 h-20 p-0 ' + (_.indexOf(selectedColorsValues, option.value) != -1 ? 'active fa fa-check' : 'not-active')"
                        :title="option.name + ' - ' + option.attribute.name"
                        :style="'background-color: ' + option.value">
                    </span>

                    <input
                        v-if="option"
                        :id="optionPrefix + '_o' + option.id"
                        class="d-none"
                        :type="multiple ? 'checkbox' : 'radio'"
                        :value="option.value"
                        v-model="selectedColorsValues"
                        @change="$dispatch('mntz:preview-color-selected', option)"
                        />

                </label>
                <label
                    v-if="option.attribute.name.toLowerCase() != constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                    class="fw-n m-0"
                    :for="optionPrefix + '_o' + option.id">

                    <input
                        v-if="option"
                        :id="optionPrefix + '_o' + option.id"
                        :type="multiple ? 'checkbox' : 'radio'"
                        :value="option.value"
                        v-model="selectedSizesValues"
                        />

                    @{{ option.name }}
                </label>
            </li>
        </ul>
    </div>

</template>
@append
