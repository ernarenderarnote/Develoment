<div class="panel">
    <div class="panel-body bg-light">
        <div
            v-show="!this.isAllOverPrintOrSimilar || !this.allOverPrintsPreviewReplaceMode"
            class="ta-c js-preview-product-canvas-container{{ !empty($suffix) ? '-'.$suffix : '' }}"
            v-loading="isLoadingPreviewCanvas{{ $suffix }}"
            :loading-options="loadingOptions">
            <canvas id="js-preview-product-canvas{{ !empty($suffix) ? '-'.$suffix : '' }}"></canvas>
        </div>
        <div v-else>
            <img
                v-if="selectedPrintFile{{ $suffix }}"
                :src="selectedPrintFile{{ $suffix }}.url"
                alt=""
                class="img-responsive"
                />
            <div v-else class="ta-c fz-30 ptb-100">
                <i class="fa fa-image text-muted fa-4x"></i>
            </div>
        </div>
    </div>
</div>
<div
    v-if="!this.isAllOverPrintOrSimilar || !this.allOverPrintsPreviewReplaceMode"
    class="position-controls"
    v-show="positionControlsCouldBeShown{{ $suffix }}">
    <div class="mtb-10 ta-c fw-b">
        @lang('labels.quality'):
        <span
            :class="previewPrintImageQuality{{ $suffix }} >= qualityValidationLowLimit ? 'color-success' : 'color-warning'">
            {{ previewPrintImageQuality<?= $suffix ?> }}% / {{ previewDpi<?= $suffix ?> }} DPI
        </span>
    </div>
    <div class="mr-10 slider-container d-ib">
        <div class="slider-component">
            <button
                type="button"
                class="btn btn-link ol-n-i"
                @click="previewDpi{{ $suffix }} = parseInt(previewDpi{{ $suffix }}) + 1">
                <i class="fa fa-minus text-muted"></i>
            </button>
            <div class="scale-wrapper w-150 d-ib va-m mlr-10">
                <nouislider
                    :slider-low-limit="dpiSliderLowLimit"
                    :slider-end-value.sync="previewDpi{{ $suffix }}"
                    :slider-high-limit="dpiSliderHighLimit"
                    :slider-step="1"
                    slider-direction="rtl"></nouislider>
            </div>
            <button
                type="button"
                class="btn btn-link ol-n-i"
                @click="previewDpi{{ $suffix }} = parseInt(previewDpi{{ $suffix }}) - 1">
                <i class="fa fa-plus text-muted"></i>
            </button>
        </div>
    </div>
    <div class="btn-group">
        <button
            type="button"
            class="btn btn-default"
            title="@lang('actions.center_vertical')"
            @click="previewProductCanvasPrintImage{{ $suffix }}.centerV().setCoords()">
            <i class="fa fa-arrows-v"></i>
        </button>
        <button
            type="button"
            class="btn btn-default"
            title="@lang('actions.center_horizontal')"
            @click="previewProductCanvasPrintImage{{ $suffix }}.centerH().setCoords()">
            <i class="fa fa-arrows-h"></i>
        </button>
        <button
            type="button"
            class="btn btn-default"
            @click="previewProductCanvasPrintImage{{ $suffix }}.center().setCoords()">
            @lang('actions.reset')
        </button>
    </div>
</div>
<div
    v-if="selectedColorsUnique.length && (!this.isAllOverPrintOrSimilar || !this.allOverPrintsPreviewReplaceMode)"
    class="ta-c mt-15">
    <div
        v-for="option in selectedColorsUnique"
        :class="'d-ib w-75 h-120 va-t m-5 p-10 cur-p thumbnail pos-r' + (currentPreviewCanvasData == option.value ? ' active' : '' )"
        @click="selectCanvasPreviewBackgroundColor(option.value)">

            <div
                v-if="previewPrintImageQuality < qualityValidationLowLimit"
                class="pos-a t r">
                <popper
                    inline-template="true"
                    content='<b>@lang('labels.we_strongly_suggest_larger_file')</b>'
                    placement="top"
                    :close-button="true">

                    <a
                        @click.prevent="showPopper = !showPopper"
                        href="#">
                        <i class="fa fa-exclamation-circle color-warning"></i>
                    </a>
                </popper>
            </div>

            <img :src="previewCanvasData{{ $suffix }}[option.id]" alt="" class="img-responsive" />
            <div class="mt-5c">
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
