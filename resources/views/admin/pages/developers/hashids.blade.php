@extends("admin.layouts.admin")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} - 
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")
    
@section("content")    
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="box box-primary">
            <div class="box-body pos-r">
                
                {!! BootForm::open([])->get()->addClass('d-b')
                    ->action('/admin/dev/hashids?action=decode') !!}
                    
                    {!! BootForm::text(trans('labels.hash'), 'decode_hash')->value($decode_hash) !!}
                        
                    {!! BootForm::submit(trans('labels.decode'))
                        ->attribute('class', 'btn btn-primary') !!}
                    
                {!! BootForm::close() !!}
                
            </div>
        </div>
    </div>
        
    <div class="col-xs-12 col-md-6">
        <div class="box box-primary">
            <div class="box-body pos-r">
                
                {!! BootForm::open([])->get()->addClass('d-b')
                    ->action('/admin/dev/hashids?action=encode') !!}
                    
                    {!! BootForm::text(trans('labels.string_to_encode'), 'encode_hash')->value($encode_hash) !!}
                        
                    {!! BootForm::submit(trans('labels.encode'))
                        ->attribute('class', 'btn btn-success') !!}
                    
                {!! BootForm::close() !!}
                
            </div>
        </div>
    </div>
</div>
@stop
