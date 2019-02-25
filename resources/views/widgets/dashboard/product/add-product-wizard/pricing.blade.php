<add-product-wizard-pricing
    inline-template="true"
    :product-title.sync="productTitle"
    :product-description.sync="productDescription"
    :selected-model-template.sync="selectedModelTemplate"
    :preview-canvas-data="previewCanvasData"
    :selected-colors-unique="selectedColorsUnique"
    :retail-prices.sync="retailPrices"
    :selected-models="selectedModels"
    :selected-print-file="selectedPrintFile"
    :selected-print-file-back="selectedPrintFileBack"
    :profit.sync="profit"
    :show-pricing="showPricing"
    :is-all-over-print-or-similar="isAllOverPrintOrSimilar"
    :all-over-prints-preview-replace-mode="allOverPrintsPreviewReplaceMode">

    <div>
        <div v-if="selectedModelTemplate !== null && showPricing" class="row mt-20">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="mb-20" v-if="previewCanvasData && selectedModelTemplate && selectedModelTemplate.image">
                    <label>@lang('labels.main_mockup_image')</label>
                    <div v-if="selectedColorsUnique.length">
                        <div
                            v-for="option in selectedColorsUnique"
                            class="d-ib w-75 h-135 va-t m-5 p-10 thumbnail pos-r">

                                <img
                                    v-if="!this.isAllOverPrintOrSimilar || !this.allOverPrintsPreviewReplaceMode"
                                    :src="previewCanvasData[option.id]"
                                    alt=""
                                    class="img-responsive" />

                                <img
                                    v-if="this.isAllOverPrintOrSimilar && this.allOverPrintsPreviewReplaceMode"
                                    :src="selectedPrintFile.url"
                                    alt=""
                                    class="img-responsive" />

                                <div class="mt-5">
                                    <tooltip placement="top" :content="option.name">
                                        <div class="ovt-e h-20">
                                            <span
                                                v-if="option.attribute.name.toLowerCase() == constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                                                class="color-control d-ib w-10 h-10 p-0"
                                                :title="option.name + ' - ' + option.attribute.name"
                                                :style="'background-color: ' + option.value">
                                            </span>
                                            @{{ option.name }}
                                        </div>
                                    </tooltip>

                                </div>
                        </div>
                    </div>
                </div>

                <div class="mb-20">
                    <label>@lang('labels.product_title')</label>
                    <input
                        class="form-control"
                        type="text"
                        name="product_title"
                        :value="productTitle"
                        />
                </div>
                <div class="mb-20">
                    <label>@lang('labels.product_description')</label>
                    <textarea
                        class="form-control res-v"
                        name="product_description"
                        rows="8"
                        v-text="productDescription"></textarea>
                </div>

                <div class="mb-20">
                    <label>@lang('labels.prices')</label>
                    <div class="row">
                        <div class="col-xs-10 col-sm-10 col-md-10">
                            Choose the mark up price and we'll auto configure your product prices
                        </div>
                    </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>@lang('labels.model')</th>
                                <th class="text-center" style="width: 180px;">
                                    @lang('labels.your_price')
                                </th>
                                <th class="text-center" style="width: 180px;">
                                    @lang('labels.retail_price')
                                </th>
                                <th class="text-center" style="width: 180px;">
                                    @lang('labels.profit')
                                </th>
                            </tr>
                            <tr class="header-row">
                                <th>@lang('labels.all_models')</th>
                                <th class="text-center">
                                    &nbsp;
                                </th>
                                <th class="text-center">
                                    @{{ getRetailPrices }}
                                </th>
                                <th class="text-center profit-margin">
                                    <input
                                        number
                                        class="d-ib form-control w-150"
                                        type="text"
                                        value="10"
                                        v-model="profit"
                                        @blur="setProfit(profit)"
                                        />
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="model in selectedModels">
                                <td>
                                    <span v-for="option in model.options">
                                        <span
                                            v-if="option.attribute.name.toLowerCase() == constants.Models.CatalogAttribute.ATTRIBUTE_COLOR"
                                            class="color-control d-ib w-10 h-10 p-0"
                                            :title="option.name + ' - ' + option.attribute.name"
                                            :style="'background-color: ' + option.value">
                                        </span>

                                        @{{ option.name }}

                                        <small class="text-muted">
                                            @{{ option.attribute.name }}
                                        </small>
                                    </span>
                                </td>
                                <td class="text-center">
                                    $@{{ modelsPrintPrices[model.id] }}
                                </td>
                                <td class="text-center">
                                    $ <input
                                        number
                                        class="d-ib form-control w-100p"
                                        type="text"
                                        :name="'retail_price['+model.id+']'"
                                        v-model="retailPrices[model.id]"
                                        @blur="validateRetailPrice(model.id)"
                                        />
                                </td>
                                <td class="text-center">
                                    <span v-if="retailProfit && retailProfit.length >= 1 && retailProfit[model.id]">
                                        $@{{ retailProfit[model.id] }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-10">
                    <div v-if="updateProduct">
                        <button
                            type="submit"
                            class="btn btn-danger btn-lg ladda-button plr-30"
                            data-style="zoom-out">
                            <span class="ladda-label">
                                @lang('actions.send_on_moderation')
                            </span>
                        </button>
                    </div>
                    <div v-else>
                        <button
                            type="submit"
                            class="btn btn-danger btn-lg ladda-button va-m plr-30"
                            data-style="zoom-out">
                            <span
                                v-if="selectedModelTemplate && selectedModelTemplate.category.isPrepaid"
                                class="ladda-label">
                                @lang('labels.pay_and_submit')
                            </span>
                            <span
                                v-if="selectedModelTemplate && !selectedModelTemplate.category.isPrepaid"
                                class="ladda-label">
                                @lang('labels.publish')
                            </span>
                        </button>
                        <div
                            v-if="selectedModelTemplate && selectedModelTemplate.category.isPrepaid"
                            class="va-m d-ib ml-15">
                            <div class="fw-b">$@{{ selectedModelTemplate.category.prepaid_amount }} @lang('labels.registration_fee_will_be_processed')</div>
                            <div>@lang('labels.product_will_be_available_after_moderation')</div>
                        </div>
                        <div
                            v-if="selectedModelTemplate && !selectedModelTemplate.category.isPrepaid"
                            class="va-m d-ib ml-15 mt-20">
                            * @lang('labels.product_will_be_live_for_purchase_within_few_minutes')
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</add-product-wizard-pricing>
