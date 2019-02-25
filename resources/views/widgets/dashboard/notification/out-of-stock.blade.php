<p>@lang('labels.products_are_out_of_stock')</p>
<h5>@lang('labels.product_variants'):</h5>
<ul class="list-group">
    @foreach($variants as $variant)
        <li class="list-group-item">{{ $variant->getFullTitle() }}</li>
    @endforeach
</ul>
