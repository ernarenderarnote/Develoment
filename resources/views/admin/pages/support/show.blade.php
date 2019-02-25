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
    <div class="col-xs-12 col-md-8 col-lg-6">
        <div class="box box-primary">
            <div class="box-body">
            
                <strong>
                    @lang('labels.user')
                </strong>
                <p class="text-muted">
                    @if ($model->user)
                        <span class="label label-primary mr-5">
                            {{ $model->user->email }}
                        </span>
                        <span class="label label-success">
                            @lang('labels.registered')
                        </span>
                    @else
                        <span class="label label-primary mr-5">
                            {{ $model->meta->from }}
                        </span>
                        <span class="label label-default">
                            @lang('labels.anonymous')
                        </span>
                    @endif
                
                </p>
                    
                @if ($model->email)
                    <p class="text-muted">
                        @lang('labels.email')
                        <span class="label label-success">
                            {{ $model->email }}
                        </span>
                    </p>
                @endif
                
                @if ($model->name)    
                    <p class="text-muted">
                        @lang('labels.name')
                        <span class="label label-success">
                            {{ $model->name }}
                        </span>
                    </p>
                @endif
                
                <hr /><!-- separator -->
            
                <strong>
                    @lang('labels.subject')
                </strong>
                <p class="text-muted">
                    {{ $model->subject }}
                </p>
                <hr /><!-- separator -->
                
                <strong>
                    @lang('labels.message')
                </strong>
                <p class="text-muted">
                    {{ $model->text }}
                </p>
                <hr /><!-- separator -->
                
                <strong>
                    @lang('labels.status')
                </strong>
                <p class="text-muted">
                    {{ $model->getStatusName() }}
                </p>
                <hr /><!-- separator -->
                
                <strong>
                    @lang('labels.updated_created')
                </strong>
                <p class="text-muted">
                    <time
                        datetime="{{ $model->updatedAtTZ() }}"
                        title="{{ $model->updatedAtTZ() }}"
                        data-format="">
                        {{ $model->updatedAtTZ() }}
                    </time>
                    /
                    <time
                        datetime="{{ $model->createdAtTZ() }}"
                        title="{{ $model->createdAtTZ() }}"
                        data-format="">
                        {{ $model->createdAtTZ() }}
                    </time>
                </p>
                <hr /><!-- separator -->
                
            </div>
        </div>
    </div>
</div>


@stop
