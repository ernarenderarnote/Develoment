{!! BootForm::open([])->post()->action('?process=1')->multipart() !!}
    <table class="table table-bordered">
        <tbody>
            @foreach($model->clientFiles as $clientFile)
                <tr>
                    <th colspan="2" class="ta-c">
                        <h4>{{ $clientFile->getLocationName() }}</h4>
                    </th>
                </tr>

                @if ($clientFile->printFile)
                    <tr>
                        <th>@lang('labels.print')</th>
                        <td>
                            <div class="d-ib thumbnail handle-transparent-image">
                                <img
                                    src="{{ $clientFile->printFile->url('thumb') }}"
                                    alt=""
                                    class="img-responsive img-rounded mb-10"
                                    />
                            </div>
                            <div>
                                <a
                                    class="btn btn-default btn-xs"
                                    target="_blank"
                                    download="{{ pathinfo($clientFile->printFile->url(), PATHINFO_BASENAME) }}"
                                    href="{{ $clientFile->printFile->url() }}">
                                        <i class="fa fa-download mr-10"></i>
                                        {{ pathinfo($clientFile->printFile->url(), PATHINFO_BASENAME) }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif

                @if ($clientFile->sourceFile)
                    <tr>
                        <th>@lang('labels.source_file')</th>
                        <td>
                            <div class="d-ib thumbnail handle-transparent-image">
                                <img
                                    src="{{ $clientFile->sourceFile->url('thumb') }}"
                                    alt=""
                                    class="img-responsive img-rounded mb-10"
                                    />
                            </div>
                            <div>
                                <a
                                    class="btn btn-default btn-xs"
                                    target="_blank"
                                    download="{{ pathinfo($clientFile->sourceFile->url(), PATHINFO_BASENAME) }}"
                                    href="{{ $clientFile->sourceFile->url() }}">
                                        <i class="fa fa-download mr-10"></i>
                                        {{ pathinfo($clientFile->sourceFile->url(), PATHINFO_BASENAME) }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endif

                <tr>
                    <th>@lang('labels.mockup')</th>
                    <td>
                        <img src="{{ $clientFile->mockup->url('thumb') }}" alt="" class="img-responsive mb-10" />
                        <a
                            class="btn btn-default btn-xs"
                            target="_blank"
                            download="{{ pathinfo($clientFile->mockup->url(), PATHINFO_BASENAME) }}"
                            href="{{ $clientFile->mockup->url() }}">
                                <i class="fa fa-download mr-10"></i>
                                {{ pathinfo($clientFile->mockup->url(), PATHINFO_BASENAME) }}
                        </a>

                        @include('widgets.dashboard.product.product-details', [
                            'product' => $model
                        ])
                    </td>
                </tr>
                <tr>
                    <th colspan="2">@lang('labels.designer_attachments')</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <ul class="list-group">
                            @foreach(\App\Models\ProductDesignerFile::listTypes() as $type => $label)
                                <li class="list-group-item">
                                    @include('admin.pages.product.show.designer-attachment', [
                                        'label' => $label,
                                        'designLocation' => $clientFile->design_location,
                                        'field' => $type,
                                        'designerFile' => $clientFile->designerFile($type),
                                        'file' => $clientFile->designerFile($type) ? $clientFile->designerFile($type)->file : null
                                    ])
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="btn-toolbar" role="toolbar">
        <div class="pull-right">
            <button class="btn btn-primary" type="submit">
                @lang('actions.upload_and_save')
            </button>
        </div>
    </div>

    <input name="save" type="hidden" value="1" />
{!! BootForm::close() !!}
