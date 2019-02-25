{!! BootForm::open([])->action('?process=1')->post() !!}

    <ul class="list-group">
        @foreach($model->variants as $variant)
            <li class="list-group-item">
                <h4>{{ $variant->getFullTitle() }}</h4>
                <div class="product-detail-widget">
                    @include('widgets.dashboard.product.variant-details', [
                        'resource' => $variant
                    ])
                </div>
                <div>
                    @lang('labels.design_location__front_chest'):
                    {!! BootForm::select(trans('labels.choose_designer_file_type'), 'product_variant_designer_file['.\App\Models\ProductClientFile::LOCATION_FRONT_CHEST.']['.$variant->id.']')
                        ->options(['' => trans('labels.not_selected')] + \App\Models\ProductDesignerFile::listTypes()->toArray())
                        ->select($variant->getDesignerFile() ? $variant->getDesignerFile()->file->type : null)
                        !!}
                </div>
            </li>
        @endforeach
    </ul>
        
    <div class="btn-toolbar" role="toolbar">
        <div class="pull-right">
            <button class="btn btn-primary" type="submit">
                @lang('actions.save_assignments')
            </button>
        </div>
    </div>
    
    <input name="save" type="hidden" value="1" />
{!! BootForm::close() !!}
