<{{ $tagName }} tag-name="{{ $tagName }}" v-ref:{{ $tagName }} inline-template="true">

    <div>
        <!-- preview modal -->
        <modal :show.sync="showPreviewFile" large="true">
            <div slot="modal-header" class="modal-header">
                <button type="button" class="close" @click="showPreviewFile = false, previewFile = null">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title" v-if="previewFile">
                    @lang('labels.preview') @{{ previewFile.name }}
                </h4>
            </div>
            <div slot="modal-body" class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-8 handle-transparent-image">
                            <img
                                v-if="previewFile && previewFile.thumb"
                                :src="previewFile.thumb"
                                alt=""
                                class="img-responsive" />
                            <img
                                v-else
                                src="{{ url('img/placeholders/placeholder-300x200.png') }}"
                                alt=""
                                class="img-responsive" />
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="col-md-12" v-if="previewFile">
                                <h4 class="ww-b">@{{ previewFile.name }}</h4>
                                <p class="text-muted">@{{ prettyBytes(previewFile.size) }}</p>
                                <p class="text-muted">
                                    <time
                                        class="d-b"
                                        :datetime="previewFile.updatedAt"
                                        :title="previewFile.updatedAt"
                                        data-format="">
                                        @{{ previewFile.updatedAt }}
                                    </time>
                                </p>

                                <p>
                                    <a
                                        target="_blank"
                                        :href="'{{ url('/dashboard/library') }}/'+previewFile.id+'/download'"
                                        class="btn btn-default w-150"
                                        :download="previewFile.name">
                                        @lang('actions.download_file')
                                    </a>
                                </p>
                                <p v-if="previewFile && previewFile.thumb">
                                    <a target="_blank" :href="previewFile.url" class="btn btn-default w-150">
                                        @lang('actions.view_full_size')
                                    </a>
                                </p>
                                <p>
                                    <button
                                        v-if="can('delete', previewFile)"
                                        type="button"
                                        class="btn btn-danger w-150"
                                        @click="showConfirmationModal = true">
                                        @lang('actions.delete_file')...
                                    </button>
                                    <button
                                        v-else
                                        type="button"
                                        class="btn btn-danger w-150 disabled">
                                        @lang('actions.delete_file')...
                                    </button>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer d-n"></div>
        </modal>

            <!-- delete confirmation modal -->
            <modal :show.sync="showConfirmationModal" small="true">
                <div slot="modal-header" class="modal-header d-n"></div>
                <div slot="modal-body" class="modal-body">
                    <h4 class="modal-title" v-if="previewFile">
                        @lang('labels.confirm_deletion')
                    </h4>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <div v-if="previewFile">
                        <button
                            class="btn btn-danger"
                            type="button"
                            @click="deleteFile(previewFile.id)">
                                @lang('actions.delete')
                        </button>
                        <button
                            class="btn btn-default"
                            type="button"
                            @click="showConfirmationModal = false">
                                @lang('actions.cancel')
                        </button>
                    </div>
                </div>
            </modal>

        <div class="row col-md-12">
            <div class="col-xs-12 col-md-12 col-sm-12 mh-150 mh-150">
                <spinner v-ref:spinner size="md"></spinner>

                <!-- uploader -->
                <div class="row">
                    <div class="col-xs-12 mb-30">
                        <div class="row">
                            <div class="col-xs-12 col-md-10">
                                <div class="input-group">
                                    <input
                                        type="search"
                                        class="form-control"
                                        v-model="filters.search"
                                        placeholder="@lang('labels.search_files')"
                                        debounce="700"
                                        />
                                    <span class="input-group-btn">
                                        <button
                                            @click.prevent="search()"
                                            class="btn btn-primary pd-8"
                                            type="button">
                                            @lang('actions.search')
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="js-print-files-library-upload-form col-xs-12 col-md-2 ta-nd">
                                <label for="{{ $tagName }}-print-library-file" class="btn btn-primary pd-8 w-150">
                                    @lang('actions.upload')
                                </label>
                                <input
                                    id="{{ $tagName }}-print-library-file"
                                    type="file"
                                    class="form-control v-h"
                                    name="file"
                                    @change="uploadFile($event)"
                                    />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- files grid -->
                <div class="row">
                    <div
                        v-if="!files.data || files.data.length == 0"
                        class="col-xs-12 col-md-12 col-sm-12">
                        <div class="alert alert-warning">
                            <h3 class="ta-c mtb-20">@lang('labels.you_have_no_files_yet')</h3>
                        </div>
                    </div>

                    <div v-for="file in files.data" class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                        <div class="thumbnail handle-transparent-image cur-p">
                            <div class="ta-c h-150" @click="openPreviewModal(file)">
                                <div class="d-ib">
                                    <img
                                        v-if="file && file.thumb"
                                        :src="file.thumb"
                                        alt=""
                                        class="img-responsive mxh-150" />
                                    <img
                                        v-else
                                        src="{{ url('img/placeholders/placeholder-300x200.png') }}"
                                        alt=""
                                        class="img-responsive mxh-150"
                                        />
                                </div>
                            </div>
                            <div class="caption">
                                <tooltip placement="top" :content="file.name">
                                    <div class="ovt-e h-20">@{{ file.name }}</div>
                                </tooltip>
                                <button
                                    v-if="onChooseCallback"
                                    class="btn btn-default btn-block"
                                    type="button"
                                    @click="onChooseCallback(file)">
                                        @lang('actions.choose')
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <!-- pagination -->
                    <div class="col-xs-12 col-md-12 col-sm-12" v-if="files.meta.pagination && files.meta.pagination.total_pages > 1">
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
                                    v-for="page in files.meta.pagination.total_pages"
                                    :class="{'active': currentPage == page+1}">
                                        <a href="#!" @click.prevent="loadPage(page+1)">
                                            @{{ page+1 }}
                                        </a>
                                </li>
                                <li :class="{'disabled': currentPage == files.meta.pagination.total_pages}">
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
    </div>

</{{ $tagName }}>
