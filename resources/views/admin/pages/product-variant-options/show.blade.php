@extends("admin.layouts.admin-layout")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} -
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")

@section("content")

<div class="row">
    <div class="col-xs-12 col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <div class="box-title">
                    @lang('labels.product_attributes')
                </div>
            </div>
            <div class="box-body">
            {!! BootForm::open([])->post()->multipart() !!}
                
                <table class="table table-responsive price-table">     
                       
                    <tbody>  
                        <tr colspan="3">
                            <th>@lang('labels.front_side')</th>
                            <th>@lang('labels.white')</th>
                            <th></th>
                            
                            @forelse($size_attributes as $key=>$value)
                                <th>{!! $value !!}</th>   
                            @empty
                            @endforelse
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_price')</td>
                            @php $price = $model->frontWhitePrice($template_id)->pluck('customer_price');   
                                 $i = 0
                            @endphp    
                            @foreach($size_attributes as $key=>$value) 
                                <td> 
                                    <input type="hidden" name="side[]" value="front_side">
                                    <input type="hidden" name="color[]" value="white"> 
                                    <input type="hidden" name="size[]" value="{{ $key }}">
                                    <input type="text"  name="customer_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>  
                                </td>
                               @php $i++ @endphp    
                            
                            @endforeach  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_print_price')</td>
                            @php $price = $model->frontWhitePrice($template_id)->pluck('customer_print_price');
                                 $i = 0 
                            @endphp  
                            @forelse($size_attributes as $key=>$value)  
                                <td>  
                                    <input type="text" name="customer_print_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>   
                                </td>
                                @php $i++ @endphp   
                            @empty
                            @endforelse  
                        </tr> 

                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_price')</td>
                            @php $price = $model->frontWhitePrice($template_id)->pluck('brand_price');
                                 $i = 0 
                            @endphp  
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="text" name="brand_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>
                                </td>
                                @php $i++ @endphp 
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_print_price')</td>
                            @php $price = $model->frontWhitePrice($template_id)->pluck('brand_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="text" name="brand_print_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>
                                </td>
                                @php $i++ @endphp 
                            @empty
                            @endforelse  
                        </tr> 
                        <!--front color-->
                        <tr colspan="3">
                            <th>@lang('labels.front_side')</th>
                            <th>@lang('labels.color')</th>
                            <th></th> 
                            @forelse($size_attributes as $key=>$value)
                                <th>{!! $value !!}</th>   
                            @empty
                            @endforelse
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_price')</td>
                            @php $price = $model->frontcolorPrice($template_id)->pluck('customer_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="hidden" name="side[]" value="front_side">
                                    <input type="hidden" name="color[]" value="color"> 
                                    <input type="hidden" name="size[]" value="{{ $key }}">
                                    <input type="text"  name="customer_price[]"  value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_print_price')</td>
                            @php $price = $model->frontcolorPrice($template_id)->pluck('customer_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="text" name="customer_print_price[]"  value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>  
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_price')</td>
                            @php $price = $model->frontcolorPrice($template_id)->pluck('brand_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td>     
                                    <input type="text" name="brand_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>  
                                </td>
                                @php $i++ @endphp    
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_print_price')</td>
                            @php $price = $model->frontcolorPrice($template_id)->pluck('brand_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="text" name="brand_print_price[]" value="{{ count($price) ? $price[$i] : "" }}" placeholder='0.00'>    
                                </td>
                              @php  $i++ @endphp;
                            @empty
                            @endforelse  
                        </tr>

                        <!--Backside-->
                        <tr colspan="3">
                            <th>@lang('labels.back_side')</th>
                            <th>@lang('labels.white')</th>
                            <th></th>
                            @forelse($size_attributes as $key=>$value)
                                <th>{!! $value !!}</th>   
                            @empty
                            @endforelse
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_price')</td>
                            @php $price = $model->backWhitePrice($template_id)->pluck('customer_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="hidden" name="side[]" value="back_side">
                                    <input type="hidden" name="color[]" value="white"> 
                                    <input type="hidden" name="size[]" value="{{ $key }}">
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}"  name="customer_price[]" placeholder='0.00'>
                                    
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_print_price')</td>
                            @php $price = $model->backWhitePrice($template_id)->pluck('customer_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                   
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}" name="customer_print_price[]" placeholder='0.00'>
                                    
                                </td>
                               @php $i++ @endphp 
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_price')</td>
                            @php $price = $model->backWhitePrice($template_id)->pluck('brand_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}" name="brand_price[]" placeholder='0.00'>
                                    
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_print_price')</td>
                            @php $price = $model->backWhitePrice($template_id)->pluck('brand_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  

                                <td> 
                                    
                                    <input type="text"  value="{{ count($price) ? $price[$i] : "" }}" name="brand_print_price[]" placeholder='0.00'>
                                    
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        <!--Back color-->
                        <tr colspan="3">
                            <th>@lang('labels.back_side')</th>
                            <th>@lang('labels.color')</th>
                            <th></th>
                            @forelse($size_attributes as $key=>$value)
                                <th>{!! $value !!}</th>   
                            @empty
                            @endforelse
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_price')</td>
                            @php $price = $model->backColorPrice($template_id)->pluck('customer_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="hidden" name="side[]" value="back_side">
                                    <input type="hidden" name="color[]" value="color"> 
                                    <input type="hidden" name="size[]" value="{{ $key }}">
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}" name="customer_price[]" placeholder='0.00'>     
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.customer_print_price')</td>
                            @php $price = $model->backColorPrice($template_id)->pluck('customer_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    
                                    <input type="text"  value="{{ count($price) ? $price[$i] : "" }}" name="customer_print_price[]" placeholder='0.00'>
                                    
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_price')</td>
                            @php $price = $model->backColorPrice($template_id)->pluck('brand_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td> 
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}"  name="brand_price[]" placeholder='0.00'>
                                </td>
                                @php $i++ @endphp    
                            @empty
                            @endforelse  
                        </tr>
                        </tr>
                            <td></td>
                            <td></td>
                            <td>@lang('labels.brand_print_price')</td>
                            @php $price = $model->backColorPrice($template_id)->pluck('brand_print_price');
                                 $i = 0 
                            @endphp 
                            @forelse($size_attributes as $key=>$value)  
                                <td>  
                                    <input type="text" value="{{ count($price) ? $price[$i] : "" }}" name="brand_print_price[]" placeholder='0.00'>      
                                </td>
                                @php $i++ @endphp
                            @empty
                            @endforelse  
                        </tr>        
                        <tr colspan="3">
                            <td>{!! BootForm::submit('Submit')->class('btn btn-info') !!}</td>                  
                        </tr>              
                    </tbody>                              
                </table>
                {!! BootForm::close() !!}                
            </div>
        </div>
    </div>
</div>
@endsection