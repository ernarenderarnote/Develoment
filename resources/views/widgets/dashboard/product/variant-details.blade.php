@if (
    !empty($variant->model)
    && !empty($variant->model->template)
    && !empty($variant->model->template->category)
)
    <div>
        @lang('labels.category'):
        {{ $variant->model->template->category->name }}
    </div>
@endif

@if (!empty($variant->model) && !empty($variant->model->template))
    <div>
        @lang('labels.model'):
        {{ $variant->model->template->name }}
    </div>
@endif

@if ($variant->model && !$variant->model->catalogOptions->isEmpty())
    @foreach($variant->model->catalogOptions as $option)
        @if ($option->catalogAttribute->value == \App\Models\CatalogAttribute::ATTRIBUTE_COLOR)
            <div>
                {{ $option->catalogAttribute->name }}:
                <span title="{{ $option->name }}" class="d-ib w-15 h-15 bd" style="background-color: {{ $option->value }}"></span>
            </div>
        @else
            <div>
                {{ $option->catalogAttribute->name }}:
                {{ $option->name }}
            </div>
        @endif
    @endforeach
@endif
<div>@lang('labels.your_price'): @price($variant->printPrice())</div>
<div>@lang('labels.retail_price'): @price($variant->retailPrice())</div>

@if (env('TURN_ON_FEATURE__PRODUCT_VARIANTS_MODERATION'))
    <hr />

    @include('widgets.dashboard.product.moderation-status', [
        'resource' => $variant
    ])

    @if (
        !Request::is('admin*')
        && (
            $variant->isNotApproved()
        )
    )
        {!! BootForm::open([])->post()->addClass('d-ib')
            ->action('/dashboard/product-variants/'.$variant->id.'/send-on-moderation') !!}

           {!! BootForm::submit(trans('actions.send_on_moderation'))
                ->attribute('class', 'btn btn-link btn-xs') !!}

        {!! BootForm::close() !!}
    @endif
@endif
