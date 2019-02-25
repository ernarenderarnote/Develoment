<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $ordersCount }}</h3>
                <p>@lang('labels.orders')</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="{{ url('/admin/orders') }}" class="small-box-footer">
                @lang('actions.more_info') <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $storesCount }}</h3>
                <p>@lang('labels.stores')</p>
            </div>
            <div class="icon">
                <i class="ion ion-shop"></i>
            </div>
            <a href="{{ url('/admin/users') }}" class="small-box-footer">
                @lang('actions.more_info') <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $usersCount }}</h3>
                <p>@lang('labels.user_registrations')</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ url('/admin/users') }}" class="small-box-footer">
                @lang('actions.more_info') <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
    
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $productsCount }}</h3>
                <p>@lang('labels.products')</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="{{ url('/admin/products') }}" class="small-box-footer">
                @lang('actions.more_info') <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->
</div>
