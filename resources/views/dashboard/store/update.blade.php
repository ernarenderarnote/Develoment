@extends('layouts.app')

@section('title')
    @lang('labels.update_store')
@stop

@section('bodyClasses', 'page')

@section('content')

<store-settings inline-template="true">
    <div class="row">

        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/dashboard/store') }}">
                        @lang('actions.stores')
                    </a>
                </li>
                <li>
                    <span class="active">
                        {{ $store->name }}
                    </span>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4 class="mt-0">
                {{ $store->name }}
            </h4>

            <tabs :active.sync="activeTab">
                <tab header="@lang('actions.store')">
                    @include('dashboard.store.update.tab-store')
                </tab>
                <tab header="@lang('actions.orders')">
                    @include('dashboard.store.update.tab-orders')
                </tab>
                <tab header="@lang('actions.api')">
                    @include('dashboard.store.update.tab-api')
                </tab>
            </tabs>
        </div>

    </div>
</store-settings>

@stop


@section('scripts')
    
@append
