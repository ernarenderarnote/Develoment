<div
    v-show="selectedModelTemplate && (selectedModelTemplate.image || selectedModelTemplate.overlay || isAllOverPrintOrSimilar)"
    class="col-xs-12 col-md-6">
    <b class="d-b ta-c">@lang('labels.front_print')</b>

    <!-- overlay download -->
    <a
        v-if="isAllOverPrintOrSimilar && selectedModelTemplate.overlay"
        :href="selectedModelTemplate.overlay.url"
        target="_blank"
        :download="selectedModelTemplate.overlay.name"
        class="btn btn-info">
            <i></i>
            @lang('actions.download_overlay')
    </a>

    <!-- preview thumbnail -->
    <div
        v-if="selectedPrintFile && selectedPrintFile.id"
        class="thumbnail m-0 pos-r">
        <img
            :src="selectedPrintFile.url"
            alt=""
            class="img-responsieve"
            />
        <div class="ta-c pos-a btn-preview-file-remove">
            <button
                @click="selectedPrintFile = null"
                class="btn btn-default"
                type="button">
                    <i class="fa fa-times"></i>
                    @lang('actions.remove')
            </button>
        </div>
        <div class="caption ww-b">
            <h6>@{{ selectedPrintFile.name }}</h6>
        </div>
    </div>

    <!-- preview uploader -->
    <button
        @click="showPrintLibrary = true"
        class="btn btn-primary btn-block"
        type="button">
            <i class="fa fa-upload"></i>
            <span v-if="selectedFiles && selectedFiles.id">@lang('actions.change_file')</span>
            <span v-else>
                <span v-if="isAllOverPrintOrSimilar">
                    @lang('actions.choose_preview')
                </span>
                <span v-else>
                    @lang('actions.choose_file')
                </span>
            </span>
    </button>

    <!-- source uploader -->
    <div class="btn-group d-b mt-5">
        <button
            @click="showSourcesLibrary = true"
            :class="'btn btn-default ' + (selectedSourceFile && selectedSourceFile.id ? 'mxw-150' : 'btn-block')"
            type="button">
                <i class="fa fa-upload"></i>
                <span v-if="selectedSourceFile && selectedSourceFile.id">
                    <tooltip placement="top" :content="selectedSourceFile.name">
                        <div class="ovt-e h-20">
                            @{{ selectedSourceFile.name }}
                        </div>
                    </tooltip>
                </span>
                <span v-else>@lang('actions.choose_source_file')</span>
        </button>
        <button
            v-if="selectedSourceFile && selectedSourceFile.id"
            @click="selectedSourceFile = null"
            class="btn btn-default pull-right"
            type="button">
                <i class="fa fa-times"></i>
        </button>
    </div>
</div>

<!-- back -->
<div
    v-show="selectedModelTemplate && (selectedModelTemplate.imageBack || selectedModelTemplate.overlayBack)"
    class="col-xs-12 col-md-6">
    <b class="d-b ta-c">@lang('labels.back_print')</b>

    <!-- overlay download -->
    <a
        v-if="isAllOverPrintOrSimilar && selectedModelTemplate.overlayBack"
        :href="selectedModelTemplate.overlayBack.url"
        target="_blank"
        :download="selectedModelTemplate.overlayBack.name"
        class="btn btn-info btn-block mb-5">
            <i></i>
            @lang('actions.download_overlay')
    </a>

    <!-- preview thumbnail -->
    <div
        v-if="selectedPrintFileBack && selectedPrintFileBack.id"
        class="thumbnail m-0 pos-r">
        <img
            :src="selectedPrintFileBack.url"
            alt=""
            class="img-responsive"
            />
        <div class="ta-c pos-a btn-block t mt-50 btn-preview-file-remove">
            <button
                @click="selectedPrintFileBack = null"
                class="btn btn-default"
                type="button">
                    <i class="fa fa-times"></i>
                    @lang('actions.remove')
            </button>
        </div>
        <div class="caption ww-b">
            <h6>@{{ selectedPrintFileBack.name }}</h6>
        </div>
    </div>

    <!-- preview uploader -->
    <button
        @click="showPrintLibraryBack = true"
        class="btn btn-primary btn-block"
        type="button"
        :disabled="!backPrintEnabled">
            <i class="fa fa-upload"></i>
            <span v-if="selectedPrintFileBack && selectedPrintFileBack.id">@lang('actions.change_file')</span>
            <span v-else>
                <span v-if="isAllOverPrintOrSimilar">
                    @lang('actions.choose_preview')
                </span>
                <span v-else>
                    @lang('actions.choose_file')
                </span>
            </span>
    </button>

    <!-- source uploader -->
    <div class="btn-group d-b mt-5">
        <button
            @click="showSourcesLibraryBack = true"
            :class="'btn btn-default ' + (selectedSourceFileBack && selectedSourceFileBack.id ? 'mxw-150' : 'btn-block')"
            type="button"
            :disabled="!backPrintEnabled">
                <i class="fa fa-upload"></i>
                <span v-if="selectedSourceFileBack && selectedSourceFileBack.id">
                    <tooltip placement="top" :content="selectedSourceFileBack.name">
                        <div class="ovt-e h-20">
                            @{{ selectedSourceFileBack.name }}
                        </div>
                    </tooltip>
                </span>
                <span v-else>@lang('actions.choose_source_file')</span>
        </button>
        <button
            v-if="selectedSourceFileBack && selectedSourceFileBack.id"
            @click="selectedSourceFileBack = null"
            class="btn btn-default pull-right"
            type="button">
                <i class="fa fa-times"></i>
        </button>
    </div>
</div>

<!-- example template -->
<div
    v-if="selectedModelTemplate && selectedModelTemplate.exampleFile"
    class="col-xs-12 col-sm-12 col-md-12 mt-5">

    <hr />

    <a
        :href="selectedModelTemplate.exampleFile.url"
        target="_blank"
        :download="selectedModelTemplate.exampleFile.name"
        class="btn btn-info btn-block">
            <i class="fa fa-download"></i>
            @lang('labels.product_template')
    </a>
    
</div>
