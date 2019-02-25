@extends('layouts.app')

@section('title')
    @lang('labels.update_order')
@stop

@section('bodyClasses', 'page')

@section('content')
    
@include('dashboard.orders.update.top-bar')
    
<div class="row">
    <div class="col-md-12">
        <order-products-update-form></order-products-update-form>
    </div>
</div>

@stop
