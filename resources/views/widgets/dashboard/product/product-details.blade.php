@if (!$product->getVariantAttributeOptions()->isEmpty())
    @foreach($product->getVariantAttributeOptions() as $attr)
        <div class="mtb-5">
            <b>{{ $attr->name }}</b>:
            @foreach($attr->selectedOptions as $option)
                @if ($attr->value == \App\Models\CatalogAttribute::ATTRIBUTE_COLOR)
                    <span title="{{ $option->name }}" class="d-ib w-15 h-15 bd" style="background-color: {{ $option->value }}"></span>
                @else
                    <span class="badge badge-secondary">{{ $option->name }}</span>
                @endif
            @endforeach
        </div>
    @endforeach
@endif
