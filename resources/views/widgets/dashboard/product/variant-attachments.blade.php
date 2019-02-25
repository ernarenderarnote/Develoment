@if (!empty($variant->files))
    @foreach ($variant->files as $file)
        <div class="ta-c d-ib">
            <div>
                <img src="{{ $file->url('thumb') }}" alt="" class="mxw-100 img-responsive" />
            </div>
            <div>@lang('labels.'.$file->pivot->type)</div>
        </div>
    @endforeach
@endif
@if (!empty($variant->mockups))
    @foreach ($variant->mockups as $file)
        <div class="ta-c d-ib">
            <div>
                <img src="{{ $file->url('thumb') }}" alt="" class="mxw-100 img-responsive" />
            </div>
            <div>@lang('labels.'.$file->pivot->type)</div>
        </div>
    @endforeach
@endif
