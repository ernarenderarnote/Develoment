<table class="table table-hover products">
    <thead>
        <tr>
            <th colspan="2">@lang('labels.product')</th>
            <th>@lang('labels.qty')</th>
            <th align="right">@lang('labels.price')</th>
            <th align="right">@lang('labels.retail')</th>
            <th align="right">@lang('labels.total')</th>
        </tr>
    </thead>

    <tbody>

        @foreach($order->variants as $variant)
        <tr class="{{ ($variant->model && $variant->model->template) ? '' : 'bg-danger' }}">
            <td class="va-t">
                @if ($variant->model && $variant->model->preview && $variant->model->preview->url())
                    <img src="{{ $variant->model->preview->url() }}" alt="" class="img-responsive w-100" />
                @endif
            </td>
            <td width="50%">
                <h4 class="m-0">
                    @if (auth()->user()->isAdmin() && $variant->product)
                        <a href="{{ url('/admin/products/'.$variant->product->id.'/show') }}">
                            {{ $variant->getFullTitle() }}
                        </a>
                    @else
                        {{ $variant->getFullTitle() }}
                    @endif
                    @if (auth()->user()->isAdmin())
                        <sup class="fz-10 text-muted">#{{ $variant->id }}</sup>
                    @endif
                </h4>
                @if (!empty($variant->model))
                    <small class="d-b">
                        @lang('labels.model'):
                        @if (auth()->user()->isAdmin())
                            @if ($variant->model->template)
                                <a href="{{ url('/admin/product-models/'.$variant->model->template->id.'/edit') }}">
                                    {{ $variant->model->template ? $variant->model->template->name : '' }}
                                </a>
                            @else
                                <span class="text-danger">
                                    @lang('labels.product_model_template_missed', [
                                        'id' => $variant->model->template_id
                                    ])
                                </span>
                            @endif
                        @else
                            {{ $variant->model->template ? $variant->model->template->name : '' }}
                        @endif
                    </small>
                @endif
                @if (!empty($showFull))
                    @if (!empty($variant->model->catalogOptions))
                        @foreach($variant->model->catalogOptions as $option)
                            <div>
                                {{ $option->name }}
                                <small class="text-muted">
                                    {{ $option->catalogAttribute->name }}
                                </small>
                            </div>
                        @endforeach
                    @endif

                    @if ($variant->files)
                        <hr />
                        <h4>@lang('labels.files')</h4>
                        <div>
                            @foreach ($variant->files as $file)
                                <div class="ta-c d-ib">
                                    <div>
                                        <a target="_blank" href="{{ $file->url() }}" download>
                                            <img src="{{ $file->url('thumb') }}" alt="" class="mxw-100 img-responsive" />
                                        </a>
                                    </div>
                                    <div>@lang('labels.'.$file->pivot->type)</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($variant->mockups)
                        <hr />
                        <h4>@lang('labels.mockups')</h4>
                        <div>
                            @foreach ($variant->mockups as $file)
                                <div class="ta-c d-ib">
                                    <div>
                                        <a target="_blank" href="{{ $file->url() }}" download>
                                            <img src="{{ $file->url('thumb') }}" alt="" class="mxw-100 img-responsive" />
                                        </a>
                                    </div>
                                    <div>@lang('labels.'.$file->pivot->type)</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </td>
            <td class="ta-c">
                {{ $variant->quantity() }}
            </td>
            <td class="ta-nd">
                @if ($variant->model && $variant->model->template)
                    @price($variant->printPrice())
                @else
                    &mdash;
                @endif
            </td>
            <td class="ta-nd">
                @price($variant->customerPaidPrice())
            </td>
            <td class="ta-nd">
                @price($variant->printPrice($variant->quantity()))
            </td>
        </tr>
        @endforeach

        <tr class="bordered">
            <td colspan="5">
                <strong>@lang('labels.subtotal')</strong>
            </td>
            <td colspan="2" align="right">
                @price($order->subtotal())
            </td>
        </tr>

        <tr class="bordered">
            <td colspan="5">
                <strong>@lang('labels.shipping_&_handling')</strong>
                {{--<em>{{ $order->getShippingMethodName() }}</em>--}}
            </td>
            <td colspan="2" align="right">
                @if ($order->getShippingPrice())
                    @price($order->getShippingPrice())
                @else
                    <div class="label label-danger">
                        @lang('labels.not_selected')
                    </div>
                @endif
            </td>
        </tr>

        <tr class="bordered">
            <td colspan="5">
                <strong>@lang('labels.total')</strong>
            </td>
            <td colspan="2" align="right">
                <strong>@price($order->total())</strong>
            </td>
        </tr>
    </tbody>
</table>
