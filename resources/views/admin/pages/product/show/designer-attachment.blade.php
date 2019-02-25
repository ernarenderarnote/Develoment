<div class="row">
    <div class="col-xs-12 col-md-3 col-lg-2">
        <label class="col-xs-12 control-label required">{{ $label }}*</label>
    </div>
    <div class="col-xs-12 col-md-9 col-lg-10">
        <div class="col-xs-12">

            @if (!empty($file))
                <a
                    class="btn btn-default btn-xs"
                    target="_blank"
                    download="{{ pathinfo($file->url(), PATHINFO_BASENAME) }}"
                    href="{{ $file->url() }}">
                        <i class="fa fa-download mr-10"></i>
                        {{ pathinfo($file->url(), PATHINFO_BASENAME) }}
                </a>

                <a href="{{ url('/admin/product-designer-files/'.$designerFile->id.'/delete') }}" class="js-confirm close fl-n" title="@lang('actions.delete_file')">
                    <i class="fa fa-times text-muted"></i>
                </a>
            @else
                @include('widgets.fileinput', [
                    'file' => null,
                    'name' => $designLocation.'__'.$field
                ])
            @endif
        </div>

        <div class="col-xs-12 mtb-10">
            {!! BootForm::select(trans('labels.choose_print_position'), 'print_position['.$designLocation.'__'.$field.']')
                ->options(['' => trans('labels.not_selected')] + \App\Models\ProductDesignerFile::listPrintPositions())
                ->select($designerFile ? $designerFile->print_position : null)
                !!}
        </div>

        <div
            v-if="availableVariants && availableVariants.{{ $designLocation }}"
            class="col-xs-12">
            <label class="control-label">
                @lang('actions.choose_variants')
            </label>
            <multiselect
                id="{{ $designLocation }}--{{ $field }}"
                :allow-empty="true"
                :close-on-select="false"
                :clear-on-select="true"
                :options="availableVariants.{{ $designLocation }}"
                :selected.sync="selectedVariants.{{ $designLocation }}.{{ $field }}"
                :searchable="true"
                :multiple="true"
                :custom-label="styleVariantSelectLabel"
                option-partial="customOptionPartial"
                select-label=""
                selected-label=""
                placeholder="@lang('actions.choose_variants')"
                key="id"
                label="name"
                @update="onVariantSelected"></multiselect>
        </div>

        <div
            v-if="selectedVariants && selectedVariants.{{ $designLocation }} && selectedVariants.{{ $designLocation }}.{{ $field }}"
            class="hidden">
            <input
                v-for="selectedVariant in selectedVariants.{{ $designLocation }}.{{ $field }}"
                type="text"
                :name="'product_variant_designer_file[{{ $designLocation }}][{{ $field }}][]'"
                :value="selectedVariant.id"
                />
        </div>

    </div>

</div>
