@extends('layouts.app')

@section('title')
    @lang('labels.catalogs')
@stop

@section('bodyClasses', 'page')

@section('content')
   
<div class="shipping-product">
    <div class="shipping-backlink"> <!-- <a href="javascript:void(0);">&lt; Back</a> --></div>
    <div class="worldwide-shippingheading">
        <h3><img src="{{ url('img/catalog/world_wide_shipping_icon.png') }}" alt="World Wide Shipping"> World Wide Shipping</h3>
    </div>
    <div class="panel panel-default product-grids">
        <table>
        <thead>
            <tr>
                <th data-col-seq="0" colspan="4">United States</th>
                <th data-col-seq="1" colspan="4">Canada</th>
                <th data-col-seq="2" colspan="4">International</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="kv-grouped-row" colspan="12">Rates</td>
            </tr>
            <tr>
                <td colspan="4">
                    <p><span class="fg-green">First Class</span> - First Product $4.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $2.00</p>
                </td>
                <td colspan="4">
                    <p><span class="fg-green">First Class</span> - First Product $9.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $3.00</p>
                </td>
                <td colspan="4">
                    <p><span class="fg-green">First Class</span> - First Product $12.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $2.00</p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <p><span class="fg-green">Priority Class</span> - First Product $6.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $2.00</p>
                </td>
                <td colspan="4">
                    <p><span class="fg-green">Priority Class </span> - First Product $14.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $3.00</p>
                </td>
                <td colspan="4">
                    <p><span class="fg-green">Priority Class </span> - First Product $20.00</p>
                    <p><span class="fg-green">+</span></p>
                    <p>Each Additional Product $3.00</p>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="worldwide-shippingheading">
        <h3><img src="{{ url('img/catalog/digital_catalogue_icon.png') }}" alt="Digital Catalog">Digital Catalog</h3>
    </div>
    <div class="panel panel-default product-grids product-grids-middle">
        <table>
        <thead>
            <tr>
                <th class="product-firstcolumn">Image</th>
                <th class="product-secondcolumn">SKU code</th>
                <th class="product-thirdcolumn">Title</th>
                <th class="product-thirdcolumn">Name</th>
                <th class="product-fifthcolumn">Prod. cost</th>
                <th class="product-fifthcolumn" >Retail price</th>
                <th class="product-7thcolumn">Profit</th>
                <th class="product-8thcolumn  product-changelink detailsToggle"> <a href="javascript:void(0);" class="fg-green"><i class="fa fa-plus" aria-hidden="true"></i></a></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="kv-grouped-row" colspan="8">Garment</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/Mens_crew_neck_3600_front.png') }}"></td>
                <td class="product-secondcolumn">3600</td>
                <td class="product-thirdcolumn">Shopify T-Shirt - Guys</td>
                <td class="product-thirdcolumn">Shopify T-Shirt - Guys</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr class="id123">
                <td class="productcolor-change productcolor-3600 just" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_NL_crew.jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #890e23" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cardinal"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #fead28" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gold"></div>
                            <div class="colorbox" style="background: #483131" data-toggle="tooltip" data-placement="top" title="" data-original-title="Brown"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #FDE4E9" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Pink"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #3d3c3d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heavy Metal"></div>
                            <div class="colorbox" style="background: #DCD6C1" data-toggle="tooltip" data-placement="top" title="" data-original-title="NATURAL"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #f2e3bb" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cream"></div>
                            <div class="colorbox" style="background: #335994" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cool Blue"></div>
                            <div class="colorbox" style="background: #3c2516" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Chocolate"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #273b32" data-toggle="tooltip" data-placement="top" title="" data-original-title="Forest Green"></div>
                            <div class="colorbox" style="background: #898d6d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Olive"></div>
                            <div class="colorbox" style="background: #b2b3ad" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Gray"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #e97b47" data-toggle="tooltip" data-placement="top" title="" data-original-title="CLASSIC ORANGE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle2.png') }}"></td>
                <td class="product-secondcolumn">N3900</td>
                <td class="product-thirdcolumn">Shopify T-Shirt - Girls</td>
                <td class="product-thirdcolumn">Shopify T-Shirt - Girls</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ladies_boyfriend_tee.jpg') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #890e23" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cardinal"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #fead28" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gold"></div>
                            <div class="colorbox" style="background: #483131" data-toggle="tooltip" data-placement="top" title="" data-original-title="Brown"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #FDE4E9" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Pink"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #3d3c3d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heavy Metal"></div>
                            <div class="colorbox" style="background: #DCD6C1" data-toggle="tooltip" data-placement="top" title="" data-original-title="NATURAL"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #f2e3bb" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cream"></div>
                            <div class="colorbox" style="background: #335994" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cool Blue"></div>
                            <div class="colorbox" style="background: #3c2516" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Chocolate"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #273b32" data-toggle="tooltip" data-placement="top" title="" data-original-title="Forest Green"></div>
                            <div class="colorbox" style="background: #898d6d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Olive"></div>
                            <div class="colorbox" style="background: #b2b3ad" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Gray"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #e97b47" data-toggle="tooltip" data-placement="top" title="" data-original-title="CLASSIC ORANGE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle3.png') }}"></td>
                <td class="product-secondcolumn">3633</td>
                <td class="product-thirdcolumn">Shopify Tank Top - Guys</td>
                <td class="product-thirdcolumn">Shopify Tank Top - Guys</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/productcolor3.png') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #3d3c3d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heavy Metal"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle4.png') }}"></td>
                <td class="product-secondcolumn">882L</td>
                <td class="product-thirdcolumn">Shopify Tank Top - Girls</td>
                <td class="product-thirdcolumn">Shopify Tank Top - Girls</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.90</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:  Girl</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/3533_Tank_sizing_chart_female.jpg') }}" alt="No image" class="img-responsive">                        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #862633" data-toggle="tooltip" data-placement="top" title="" data-original-title="INDEPENDENCE RED"></div>
                            <div class="colorbox" style="background: #509E2F" data-toggle="tooltip" data-placement="top" title="" data-original-title="Green Apple"></div>
                            <div class="colorbox" style="background: #00b4d1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Caribbean Blue"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle5.png') }}"></td>
                <td class="product-secondcolumn">F170</td>
                <td class="product-thirdcolumn">Shopify - Pull Over Hoodie</td>
                <td class="product-thirdcolumn">Shopify - Pull Over Hoodie</td>
                <td class="product-fifthcolumn">$24.00</td>
                <td class="product-fifthcolumn" >$39.99</td>
                <td class="product-7thcolumn">$15.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:  Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_hoodie (1).jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #e8e8e8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ash"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #4b4d52" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal Heather"></div>
                            <div class="colorbox" style="background: #bd363a" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deep Red"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #3c2516" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Chocolate"></div>
                            <div class="colorbox" style="background: #7ba4db" data-toggle="tooltip" data-placement="top" title="" data-original-title="CAROLINA BLUE"></div>
                            <div class="colorbox" style="background: #00337f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deep Royal"></div>
                            <div class="colorbox" style="background: #bdb7b9" data-toggle="tooltip" data-placement="top" title="" data-original-title="LIGHT STEEL"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle6.png') }}"></td>
                <td class="product-secondcolumn">F260</td>
                <td class="product-thirdcolumn">Shopify - Sweatshirt</td>
                <td class="product-thirdcolumn">Shopify - Sweatshirt</td>
                <td class="product-fifthcolumn">$18.00</td>
                <td class="product-fifthcolumn" >$39.99</td>
                <td class="product-7thcolumn">$21.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:  Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew.jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #586369" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal"></div>
                            <div class="colorbox" style="background: #e8e8e8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ash"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #4b4d52" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal Heather"></div>
                            <div class="colorbox" style="background: #bd363a" data-toggle="tooltip" data-placement="top" title="" data-original-title="Deep Red"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #3c2516" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Chocolate"></div>
                            <div class="colorbox" style="background: #7ba4db" data-toggle="tooltip" data-placement="top" title="" data-original-title="CAROLINA BLUE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle7.png') }}"></td>
                <td class="product-secondcolumn">1560</td>
                <td class="product-thirdcolumn">Shopify - Scoop Neck Round Bottom</td>
                <td class="product-thirdcolumn">Shopify - Scoop Neck Round Bottom</td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn" >$25.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes:  S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Ladies_Ideal_Dolman.jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle8.png') }}"></td>
                <td class="product-secondcolumn">N1533</td>
                <td class="product-thirdcolumn">Shopify - Racerback Tank</td>
                <td class="product-thirdcolumn">Shopify - Racerback Tank</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/imgs15.png') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #ccd1d4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Silver"></div>
                            <div class="colorbox" style="background: #b8407a" data-toggle="tooltip" data-placement="top" title="" data-original-title="RASPBERRY"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                            <div class="colorbox" style="background: #9ad6e1" data-toggle="tooltip" data-placement="top" title="" data-original-title="CANCUN"></div>
                            <div class="colorbox" style="background: #be97c1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lilac"></div>
                            <div class="colorbox" style="background: #970315" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scarlet"></div>
                            <div class="colorbox" style="background: #feb182" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Orange"></div>
                            <div class="colorbox" style="background: #25364e" data-toggle="tooltip" data-placement="top" title="" data-original-title="INDIGO"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle9.png') }}"></td>
                <td class="product-secondcolumn">3400L</td>
                <td class="product-thirdcolumn">Shopify - V Neck - Girls</td>
                <td class="product-thirdcolumn">Shopify - V Neck - Girls</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_womans_sporty_vneck_new.jpg') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #c6e970" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neon Green"></div>
                            <div class="colorbox" style="background: #dee24f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neon Yellow"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #b8407a" data-toggle="tooltip" data-placement="top" title="" data-original-title="RASPBERRY"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #5A224A" data-toggle="tooltip" data-placement="top" title="" data-original-title="plum"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #be97c1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lilac"></div>
                            <div class="colorbox" style="background: #25364e" data-toggle="tooltip" data-placement="top" title="" data-original-title="INDIGO"></div>
                            <div class="colorbox" style="background: #ffaacd" data-toggle="tooltip" data-placement="top" title="" data-original-title="NEON HTHR PINK"></div>
                            <div class="colorbox" style="background: #5f595b" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK GRAY"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="TAHITI BLUE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle10.png') }}"></td>
                <td class="product-secondcolumn">N3200</td>
                <td class="product-thirdcolumn">Shopify - V Neck - Guys</td>
                <td class="product-thirdcolumn">Shopify - V Neck - Guys</td>
                <td class="product-fifthcolumn">$13.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$11.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizing_Chart_Premium_v.jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #890e23" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cardinal"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #353434" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #465866" data-toggle="tooltip" data-placement="top" title="" data-original-title="Indigo"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle11.png') }}"></td>
                <td class="product-secondcolumn">3006</td>
                <td class="product-thirdcolumn">Shopify - Long Tee Long Tee (Long Back)</td>
                <td class="product-thirdcolumn">Shopify - Long Tee Long Tee (Long Back)</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn" >$29.99</td>
                <td class="product-7thcolumn">$14.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Long_Tee.jpg') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #554f53" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK GRY HEATHER"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle12.png') }}"></td>
                <td class="product-secondcolumn">5624</td>
                <td class="product-thirdcolumn">Shopify - Lean Tee (Split Seam)</td>
                <td class="product-thirdcolumn">Shopify - Lean Tee (Split Seam)</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn" >$29.99</td>
                <td class="product-7thcolumn">$14.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_long_lean-tee.jpg') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #ccd1d4" data-toggle="tooltip" data-placement="top" title="" data-original-title="Silver"></div>
                            <div class="colorbox" style="background: #707372" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER GRAPHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle13.png') }}"></td>
                <td class="product-secondcolumn">N3530</td>
                <td class="product-thirdcolumn">Shopify - Scoop Neck-F</td>
                <td class="product-thirdcolumn">Shopify - Scoop Neck-F</td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$10.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes: S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ladies_perfect_scoop.jpg') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #c6e970" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neon Green"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #be97c1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lilac"></div>
                            <div class="colorbox" style="background: #feb182" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Orange"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                            <div class="colorbox" style="background: #5f595b" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK GRAY"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle14.png') }}"></td>
                <td class="product-secondcolumn">6488</td>
                <td class="product-thirdcolumn"> Shopify - Relaxed Jersey Tank</td>
                <td class="product-thirdcolumn">Shopify - Relaxed Jersey Tank</td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$10.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_girls_relaxed_tank.jpg') }}" alt="No image" class="img-responsive">                        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #444444" data-toggle="tooltip" data-placement="top" title="" data-original-title="DK GREY HTHR"></div>
                            <div class="colorbox" style="background: #908b8c" data-toggle="tooltip" data-placement="top" title="" data-original-title="DEEP HEATHER"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #fbb6c8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pink"></div>
                            <div class="colorbox" style="background: #21217a" data-toggle="tooltip" data-placement="top" title="" data-original-title="NAVY"></div>
                            <div class="colorbox" style="background: #96e0b0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Leaf"></div>
                            <div class="colorbox" style="background: #1D4F91" data-toggle="tooltip" data-placement="top" title="" data-original-title="TRUE ROYAL"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle15.png') }}"></td>
                <td class="product-secondcolumn">PL401</td>
                <td class="product-thirdcolumn"> Shopify T-Shirt - Unisex - Sublimation</td>
                <td class="product-thirdcolumn">Shopify T-Shirt - Unisex - Sublimation</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn" >$0.00</td>
                <td class="product-7thcolumn">$00.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/imgs20.png') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle16.png') }}"></td>
                <td class="product-secondcolumn">PL408</td>
                <td class="product-thirdcolumn"> Shopify Tank Top - Unisex - Sublimation</td>
                <td class="product-thirdcolumn">Shopify Tank Top - Unisex - Sublimation</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn" >$0.00</td>
                <td class="product-7thcolumn">$00.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: XS / S / M / L / XL</p>
                        <img src="{{ url('img/catalog/21142185540754bfcaafe74da.png') }}" alt="No image" class="img-responsive">                      
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle17.png') }}"></td>
                <td class="product-secondcolumn">Monag S/S</td>
                <td class="product-thirdcolumn"> Shopify T-Shirt Black Back - Unisex - Sublimation</td>
                <td class="product-thirdcolumn">Shopify T-Shirt Black Back - Unisex - Sublimation</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn" >$0.00</td>
                <td class="product-7thcolumn">$00.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/imgs22.png') }}" alt="No image" class="img-responsive">                       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #ffffff" data-toggle="tooltip" data-placement="top" title="" data-original-title="White/Black"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle18.png') }}"></td>
                <td class="product-secondcolumn">Unisex Hoodie</td>
                <td class="product-thirdcolumn"> Shopify Unisex Hoodie - Sublimation</td>
                <td class="product-thirdcolumn">Shopify Unisex Hoodie - Sublimation</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn" >$0.00</td>
                <td class="product-7thcolumn">$00.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle19.png') }}"></td>
                <td class="product-secondcolumn">Unisex Crew Hoodie</td>
                <td class="product-thirdcolumn"> Shopify - Unisex Hoodie - Sublimation</td>
                <td class="product-thirdcolumn">Shopify - Unisex Hoodie - Sublimation</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn" >$0.00</td>
                <td class="product-7thcolumn">$00.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_hoodie (2).jpg') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle20.png') }}"></td>
                <td class="product-secondcolumn">3501</td>
                <td class="product-thirdcolumn"> Shopify - Long Sleeve</td>
                <td class="product-thirdcolumn">Shopify - Long Sleeve</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn" >$24.99</td>
                <td class="product-7thcolumn">$9.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/imgs23.png') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #60615b" data-toggle="tooltip" data-placement="top" title="" data-original-title="Asphalt"></div>
                            <div class="colorbox" style="background: #890e23" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cardinal"></div>
                            <div class="colorbox" style="background: #e8e8e8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ash"></div>
                            <div class="colorbox" style="background: #b8b3af" data-toggle="tooltip" data-placement="top" title="" data-original-title="Athletic Heather"></div>
                            <div class="colorbox" style="background: #444444" data-toggle="tooltip" data-placement="top" title="" data-original-title="DK GREY HTHR"></div>
                            <div class="colorbox" style="background: #908b8c" data-toggle="tooltip" data-placement="top" title="" data-original-title="DEEP HEATHER"></div>
                            <div class="colorbox" style="background: #2e4227" data-toggle="tooltip" data-placement="top" title="" data-original-title="Olive"></div>
                            <div class="colorbox" style="background: #21217a" data-toggle="tooltip" data-placement="top" title="" data-original-title="NAVY"></div>
                            <div class="colorbox" style="background: #5f595b" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Grey"></div>
                            <div class="colorbox" style="background: #4D5154" data-toggle="tooltip" data-placement="top" title="" data-original-title="CHRCL BLCK TRBLN"></div>
                            <div class="colorbox" style="background: #6c6f70" data-toggle="tooltip" data-placement="top" title="" data-original-title="DRK GREY HEATHER"></div>
                            <div class="colorbox" style="background: #b7b1b3" data-toggle="tooltip" data-placement="top" title="" data-original-title="GREY TRIBLEND"></div>
                            <div class="colorbox" style="background: #a87b66" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER BROWN"></div>
                            <div class="colorbox" style="background: #521b20" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER CARDINAL"></div>
                            <div class="colorbox" style="background: #3c473f" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER FOREST"></div>
                            <div class="colorbox" style="background: #707d83" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER SLATE"></div>
                            <div class="colorbox" style="background: #466c75" data-toggle="tooltip" data-placement="top" title="" data-original-title="HTHR DEEP TEAL"></div>
                            <div class="colorbox" style="background: #964959" data-toggle="tooltip" data-placement="top" title="" data-original-title="MAROON TRIBLEND"></div>
                            <div class="colorbox" style="background: #37355a" data-toggle="tooltip" data-placement="top" title="" data-original-title="NAVY TRIBLEND"></div>
                            <div class="colorbox" style="background: #1e1d1b" data-toggle="tooltip" data-placement="top" title="" data-original-title="SOLID BLK TRIBLN"></div>
                            <div class="colorbox" style="background: #2585dc" data-toggle="tooltip" data-placement="top" title="" data-original-title="TRUE ROYAL TRBLN"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle21.png') }}"></td>
                <td class="product-secondcolumn">420</td>
                <td class="product-thirdcolumn"> Shopify - Baseball Tee</td>
                <td class="product-thirdcolumn">Shopify - Baseball Tee</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn" >$27.99</td>
                <td class="product-7thcolumn">$12.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Baseball_jersey.jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #353434" data-toggle="tooltip" data-placement="top" title="" data-original-title="White/ Black"></div>
                            <div class="colorbox" style="background: #2f304f" data-toggle="tooltip" data-placement="top" title="" data-original-title="White/ Navy"></div>
                            <div class="colorbox" style="background: #D60024" data-toggle="tooltip" data-placement="top" title="" data-original-title="White/ Red"></div>
                            <div class="colorbox" style="background: #4a51a1" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ PURPLE"></div>
                            <div class="colorbox" style="background: #117a69" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ DRK GREEN"></div>
                            <div class="colorbox" style="background: #f1ab3b" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ GOLD"></div>
                            <div class="colorbox" style="background: #20a067" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ KELLY"></div>
                            <div class="colorbox" style="background: #8f4566" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ MAROON"></div>
                            <div class="colorbox" style="background: #e76939" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ ORANGE"></div>
                            <div class="colorbox" style="background: #0762b9" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE/ ROYAL"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle22.png') }}"></td>
                <td class="product-secondcolumn">G640</td>
                <td class="product-thirdcolumn"> Shopify - T-Shirt (Cost Efficient)</td>
                <td class="product-thirdcolumn">Shopify - T-Shirt (Cost Efficient)</td>
                <td class="product-fifthcolumn">$9.99</td>
                <td class="product-fifthcolumn">$29.99</td>
                <td class="product-7thcolumn">$20.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_g640_Crew.jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #64c425" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lime"></div>
                            <div class="colorbox" style="background: #643040" data-toggle="tooltip" data-placement="top" title="" data-original-title="Blackberry"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #c3346a" data-toggle="tooltip" data-placement="top" title="" data-original-title="Berry"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #bdd357" data-toggle="tooltip" data-placement="top" title="" data-original-title="KIWI"></div>
                            <div class="colorbox" style="background: #d33846" data-toggle="tooltip" data-placement="top" title="" data-original-title="PAPRIKA"></div>
                            <div class="colorbox" style="background: #532c61" data-toggle="tooltip" data-placement="top" title="" data-original-title="PURPLE"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #DCD6C1" data-toggle="tooltip" data-placement="top" title="" data-original-title="NATURAL"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #3c2516" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Chocolate"></div>
                            <div class="colorbox" style="background: #273b32" data-toggle="tooltip" data-placement="top" title="" data-original-title="Forest Green"></div>
                            <div class="colorbox" style="background: #97999B" data-toggle="tooltip" data-placement="top" title="" data-original-title="SPORT GREY"></div>
                            <div class="colorbox" style="background: #7ba4db" data-toggle="tooltip" data-placement="top" title="" data-original-title="CAROLINA BLUE"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                            <div class="colorbox" style="background: #f27eb2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Azalea"></div>
                            <div class="colorbox" style="background: #750c2a" data-toggle="tooltip" data-placement="top" title="" data-original-title="ANTIQ CHERRY RED"></div>
                            <div class="colorbox" style="background: #db076d" data-toggle="tooltip" data-placement="top" title="" data-original-title="ANTIQ HELICONIA"></div>
                            <div class="colorbox" style="background: #015b80" data-toggle="tooltip" data-placement="top" title="" data-original-title="ANTQUE SAPPHIRE"></div>
                            <div class="colorbox" style="background: #d10134" data-toggle="tooltip" data-placement="top" title="" data-original-title="CHERRY RED"></div>
                            <div class="colorbox" style="background: #7873a9" data-toggle="tooltip" data-placement="top" title="" data-original-title="CIEL BLUE"></div>
                            <div class="colorbox" style="background: #fe738e" data-toggle="tooltip" data-placement="top" title="" data-original-title="CORAL SILK"></div>
                            <div class="colorbox" style="background: #fed201" data-toggle="tooltip" data-placement="top" title="" data-original-title="Daisy"></div>
                            <div class="colorbox" style="background: #4b4a50" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK HEATHER"></div>
                            <div class="colorbox" style="background: #4bb257" data-toggle="tooltip" data-placement="top" title="" data-original-title="ELECTRIC GREEN"></div>
                            <div class="colorbox" style="background: #bcbbc1" data-toggle="tooltip" data-placement="top" title="" data-original-title="GRAPHITE HEATHER"></div>
                            <div class="colorbox" style="background: #8aacd1" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER INDIGO"></div>
                            <div class="colorbox" style="background: #e68464" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER ORANGE"></div>
                            <div class="colorbox" style="background: #614889" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER PURPLE"></div>
                            <div class="colorbox" style="background: #e53e5c" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER RED"></div>
                            <div class="colorbox" style="background: #0b85c6" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER ROYAL"></div>
                            <div class="colorbox" style="background: #44c8fe" data-toggle="tooltip" data-placement="top" title="" data-original-title="HEATHER SAPPHIRE"></div>
                            <div class="colorbox" style="background: #51524a" data-toggle="tooltip" data-placement="top" title="" data-original-title="HTH MILITARY GRN"></div>
                            <div class="colorbox" style="background: #18ab6a" data-toggle="tooltip" data-placement="top" title="" data-original-title="HTHR IRISH GREEN"></div>
                            <div class="colorbox" style="background: #43627f" data-toggle="tooltip" data-placement="top" title="" data-original-title="INDIGO BLUE"></div>
                            <div class="colorbox" style="background: #63a1de" data-toggle="tooltip" data-placement="top" title="" data-original-title="IRIS"></div>
                            <div class="colorbox" style="background: #01a461" data-toggle="tooltip" data-placement="top" title="" data-original-title="IRISH GREEN"></div>
                            <div class="colorbox" style="background: #099798" data-toggle="tooltip" data-placement="top" title="" data-original-title="JADE DOME"></div>
                            <div class="colorbox" style="background: #f5e48c" data-toggle="tooltip" data-placement="top" title="" data-original-title="CORNSILK"></div>
                            <div class="colorbox" style="background: #395499" data-toggle="tooltip" data-placement="top" title="" data-original-title="METRO BLUE"></div>
                            <div class="colorbox" style="background: #0b8fbf" data-toggle="tooltip" data-placement="top" title="" data-original-title="SAPPHIRE"></div>
                            <div class="colorbox" style="background: #1198b5" data-toggle="tooltip" data-placement="top" title="" data-original-title="TROPICAL BLUE"></div>
                            <div class="colorbox" style="background: #f0d29e" data-toggle="tooltip" data-placement="top" title="" data-original-title="YELLOW HAZE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle23.png') }}"></td>
                <td class="product-secondcolumn">3602</td>
                <td class="product-thirdcolumn"> Shopify - Long Tee Long Tee (Round Bottom)</td>
                <td class="product-thirdcolumn">Shopify - Long Tee Long Tee (Round Bottom)</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn">$29.99</td>
                <td class="product-7thcolumn">$14.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Long_Tee (1).jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle24.png') }}"></td>
                <td class="product-secondcolumn">6680</td>
                <td class="product-thirdcolumn"> Shopify - Crop Top Tank</td>
                <td class="product-thirdcolumn">Shopify - Crop Top Tank</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$9.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes:  S/S / M/L</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_crop-tank (1).jpg') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #b0b0b2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather Gray"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle25.png') }}"></td>
                <td class="product-secondcolumn">6681</td>
                <td class="product-thirdcolumn"> Shopify - Crop Top Tee</td>
                <td class="product-thirdcolumn">Shopify - Crop Top Tee</td>
                <td class="product-fifthcolumn">$15.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$9.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes:  XS/S / M/L</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_crop-tee.jpg') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #908b8c" data-toggle="tooltip" data-placement="top" title="" data-original-title="DEEP HEATHER"></div>
                            <div class="colorbox" style="background: #fc9a6b" data-toggle="tooltip" data-placement="top" title="" data-original-title="CORAL"></div>
                            <div class="colorbox" style="background: #1D4F91" data-toggle="tooltip" data-placement="top" title="" data-original-title="TRUE ROYAL"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle26.png') }}"></td>
                <td class="product-secondcolumn">6210</td>
                <td class="product-thirdcolumn">Shopify - Mens CVC T-Shirt</td>
                <td class="product-thirdcolumn">Shopify - Mens CVC T-Shirt  </td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn">$29.99</td>
                <td class="product-7thcolumn">$15.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_mens_cvc.jpg') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #dee24f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neon Yellow"></div>
                            <div class="colorbox" style="background: #890e23" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cardinal"></div>
                            <div class="colorbox" style="background: #586369" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #00d9f2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tahiti Blue"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #f2e3bb" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cream"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #898d6d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Olive"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                            <div class="colorbox" style="background: #25364e" data-toggle="tooltip" data-placement="top" title="" data-original-title="INDIGO"></div>
                            <div class="colorbox" style="background: #c6e48b" data-toggle="tooltip" data-placement="top" title="" data-original-title="Apple Green"></div>
                            <div class="colorbox" style="background: #a9a5a0" data-toggle="tooltip" data-placement="top" title="" data-original-title="SILK"></div>
                            <div class="colorbox" style="background: #523b3b" data-toggle="tooltip" data-placement="top" title="" data-original-title="ESPRESSO"></div>
                            <div class="colorbox" style="background: #8e8b97" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK HTHR GRAY"></div>
                            <div class="colorbox" style="background: #00b5d0" data-toggle="tooltip" data-placement="top" title="" data-original-title="BONDI BLUE"></div>
                            <div class="colorbox" style="background: #c3f365" data-toggle="tooltip" data-placement="top" title="" data-original-title="NEON HTHR GREEN"></div>
                            <div class="colorbox" style="background: #46517f" data-toggle="tooltip" data-placement="top" title="" data-original-title="STORM"></div>
                            <div class="colorbox" style="background: #b0dee4" data-toggle="tooltip" data-placement="top" title="" data-original-title="ICE BLUE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle27.png') }}"></td>
                <td class="product-secondcolumn">6610</td>
                <td class="product-thirdcolumn">Shopify - Girls CVC T-Shirt</td>
                <td class="product-thirdcolumn">Shopify - Girls CVC T-Shirt</td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$10.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Girl</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Ladies_CVC.jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #586369" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal"></div>
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                            <div class="colorbox" style="background: #a8937f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stone Gray"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #01a59e" data-toggle="tooltip" data-placement="top" title="" data-original-title="TEAL"></div>
                            <div class="colorbox" style="background: #b8407a" data-toggle="tooltip" data-placement="top" title="" data-original-title="RASPBERRY"></div>
                            <div class="colorbox" style="background: #5A224A" data-toggle="tooltip" data-placement="top" title="" data-original-title="plum"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #898d6d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Olive"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                            <div class="colorbox" style="background: #be97c1" data-toggle="tooltip" data-placement="top" title="" data-original-title="Lilac"></div>
                            <div class="colorbox" style="background: #970315" data-toggle="tooltip" data-placement="top" title="" data-original-title="Scarlet"></div>
                            <div class="colorbox" style="background: #a9a5a0" data-toggle="tooltip" data-placement="top" title="" data-original-title="SILK"></div>
                            <div class="colorbox" style="background: #523b3b" data-toggle="tooltip" data-placement="top" title="" data-original-title="ESPRESSO"></div>
                            <div class="colorbox" style="background: #8e8b97" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK HTHR GRAY"></div>
                            <div class="colorbox" style="background: #00b5d0" data-toggle="tooltip" data-placement="top" title="" data-original-title="BONDI BLUE"></div>
                            <div class="colorbox" style="background: #46517f" data-toggle="tooltip" data-placement="top" title="" data-original-title="STORM"></div>
                            <div class="colorbox" style="background: #8b619f" data-toggle="tooltip" data-placement="top" title="" data-original-title="PURPLE BERRY"></div>
                            <div class="colorbox" style="background: #762241" data-toggle="tooltip" data-placement="top" title="" data-original-title="LUSH"></div>
                            <div class="colorbox" style="background: #b0dee4" data-toggle="tooltip" data-placement="top" title="" data-original-title="ICE BLUE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle28.png') }}"></td>
                <td class="product-secondcolumn">6240</td>
                <td class="product-thirdcolumn">Shopify - Mens CVC V-Neck</td>
                <td class="product-thirdcolumn">Shopify - Mens CVC V-Neck</td>
                <td class="product-fifthcolumn">$14.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$10.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Mens_CVC_Vneck.jpg') }}" alt="No image" class="img-responsive">        
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #dee24f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Neon Yellow"></div>
                            <div class="colorbox" style="background: #586369" data-toggle="tooltip" data-placement="top" title="" data-original-title="Charcoal"></div>
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                            <div class="colorbox" style="background: #a8937f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Stone Gray"></div>
                            <div class="colorbox" style="background: #01a4c5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Turquoise"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #f5e09f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Banana Cream"></div>
                            <div class="colorbox" style="background: #162652" data-toggle="tooltip" data-placement="top" title="" data-original-title="Midnight Navy"></div>
                            <div class="colorbox" style="background: #cfc6bd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sand"></div>
                            <div class="colorbox" style="background: #f2e3bb" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cream"></div>
                            <div class="colorbox" style="background: #6f6437" data-toggle="tooltip" data-placement="top" title="" data-original-title="Military Green"></div>
                            <div class="colorbox" style="background: #918076" data-toggle="tooltip" data-placement="top" title="" data-original-title="Warm Gray"></div>
                            <div class="colorbox" style="background: #c0e9e0" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mint"></div>
                            <div class="colorbox" style="background: #c6e48b" data-toggle="tooltip" data-placement="top" title="" data-original-title="Apple Green"></div>
                            <div class="colorbox" style="background: #a9a5a0" data-toggle="tooltip" data-placement="top" title="" data-original-title="SILK"></div>
                            <div class="colorbox" style="background: #523b3b" data-toggle="tooltip" data-placement="top" title="" data-original-title="ESPRESSO"></div>
                            <div class="colorbox" style="background: #8e8b97" data-toggle="tooltip" data-placement="top" title="" data-original-title="DARK HTHR GRAY"></div>
                            <div class="colorbox" style="background: #00b5d0" data-toggle="tooltip" data-placement="top" title="" data-original-title="BONDI BLUE"></div>
                            <div class="colorbox" style="background: #c3f365" data-toggle="tooltip" data-placement="top" title="" data-original-title="NEON HTHR GREEN"></div>
                            <div class="colorbox" style="background: #b0dee4" data-toggle="tooltip" data-placement="top" title="" data-original-title="ICE BLUE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle29.png') }}"></td>
                <td class="product-secondcolumn">Leggings</td>
                <td class="product-thirdcolumn">Shopify - Leggings</td>
                <td class="product-thirdcolumn">Shopify - Leggings</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  XS/S / M/L</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle30.png') }}"></td>
                <td class="product-secondcolumn">Yoga Pants</td>
                <td class="product-thirdcolumn">Shopify - Yoga Pants  </td>
                <td class="product-thirdcolumn">Shopify - Yoga Pants  </td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  XS/S / M/L</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle31.png') }}"></td>
                <td class="product-secondcolumn">Unisex Crew Sweater</td>
                <td class="product-thirdcolumn">Shopify - Unisex Crew Sweater</td>
                <td class="product-thirdcolumn">Shopify - Unisex Crew Sweater</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew.jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle32.png') }}"></td>
                <td class="product-secondcolumn">3902-doggie-tank</td>
                <td class="product-thirdcolumn">Shopify - Doggie Tank</td>
                <td class="product-thirdcolumn">Shopify - Doggie Tank</td>
                <td class="product-fifthcolumn">$12.00</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$7.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Unisex</p>
                        <p>Sizes:  XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_Doggie_rib_tank.jpg') }}" alt="No image" class="img-responsive">       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #fbb6c8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pink"></div>
                            <div class="colorbox" style="background: #bee8f3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Light Blue"></div>
                            <div class="colorbox" style="background: #b8407a" data-toggle="tooltip" data-placement="top" title="" data-original-title="RASPBERRY"></div>
                            <div class="colorbox" style="background: #B3BCCC" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle33.png') }}"></td>
                <td class="product-secondcolumn">3905-DOG-BANDANA</td>
                <td class="product-thirdcolumn">Shopify Doggie Bandana</td>
                <td class="product-thirdcolumn">Shopify - Doggie Bandana</td>
                <td class="product-fifthcolumn">$9.00</td>
                <td class="product-fifthcolumn">$14.99</td>
                <td class="product-7thcolumn">$5.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  OS</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #fee06e" data-toggle="tooltip" data-placement="top" title="" data-original-title="Yellow"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle33.png') }}"></td>
                <td class="product-secondcolumn">3905-DOG-BANDANA</td>
                <td class="product-thirdcolumn">Shopify Doggie Bandana</td>
                <td class="product-thirdcolumn">Shopify - Doggie Bandana</td>
                <td class="product-fifthcolumn">$9.00</td>
                <td class="product-fifthcolumn">$14.99</td>
                <td class="product-7thcolumn">$5.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender: Guy</p>
                        <p>Sizes:  OS</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #fee06e" data-toggle="tooltip" data-placement="top" title="" data-original-title="Yellow"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle34.png') }}"></td>
                <td class="product-secondcolumn">N6021</td>
                <td class="product-thirdcolumn">Shopify - Unisex Triblend Long-Sleeve Hoody</td>
                <td class="product-thirdcolumn">Shopify - Unisex Triblend Long-Sleeve Hoody</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: XS / S / M / L / XL / 2XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_uni_ls_hoodie.jpg') }}" alt="No image" class="img-responsive">                           
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #fee06e" data-toggle="tooltip" data-placement="top" title="" data-original-title="Yellow"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #fc9eb5" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hot Pink"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle35.png') }}"></td>
                <td class="product-secondcolumn">AA2089</td>
                <td class="product-thirdcolumn">Fashion Forward - NON PRINTABLE ITEM - Sale As Is</td>
                <td class="product-thirdcolumn">Fashion Forward - NON PRINTABLE ITEM - Sale As Is</td>
                <td class="product-fifthcolumn">$16.00</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$3.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #41533b" data-toggle="tooltip" data-placement="top" title="" data-original-title="CAMO"></div>
                            <div class="colorbox" style="background: #003594" data-toggle="tooltip" data-placement="top" title="" data-original-title="STARS"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/Vegan_AF_xmas_web.jpg') }}"></td>
                <td class="product-secondcolumn">F260</td>
                <td class="product-thirdcolumn">Vegan AF Xmas Sweater</td>
                <td class="product-thirdcolumn">Vegan AF Xmas Sweater</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew14776902215813c36de510015102610205a04c11c787c4.jpg') }}" alt="No image" class="img-responsive">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #278835" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly Green"></div>
                    </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/Spiritual_AF_xmas_web.jpg') }}"></td>
                <td class="product-secondcolumn">F260</td>
                <td class="product-thirdcolumn">Spiritual AF Xmas Sweater</td>
                <td class="product-thirdcolumn">Spiritual AF Xmas Sweater</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew14776902215813c36de510015102610205a04c11c787c4.jpg') }}" alt="No image" class="img-responsive">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                    </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/Woke_AF_xmas_web.jpg') }}"></td>
                <td class="product-secondcolumn">F260</td>
                <td class="product-thirdcolumn">Woke AF Xmas Sweater</td>
                <td class="product-thirdcolumn">Woke AF Xmas Sweater</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew14776902215813c36de510015102610205a04c11c787c4.jpg') }}" alt="No image" class="img-responsive">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                    </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/Spiritual_as_hell_xmas_web.jpg') }}"></td>
                <td class="product-secondcolumn">F260</td>
                <td class="product-thirdcolumn">Spiritual as Hell Xmas Sweater</td>
                <td class="product-thirdcolumn">Spiritual as Hell Xmas Sweater</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Gender:Guy</p>
                        <p>Sizes: S / M / L / XL / 2XL / 3XL</p>
                        <img src="{{ url('img/catalog/KZ_-_Sizin_Chart_ls_crew14776902215813c36de510015102610205a04c11c787c4.jpg') }}" alt="No image" class="img-responsive">
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #5d1399" data-toggle="tooltip" data-placement="top" title="" data-original-title="Purple Rush"></div>
                    </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Prints</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn">A0-PSTR-KZ</td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A0</td>
                <td class="product-fifthcolumn">$20.15</td>
                <td class="product-fifthcolumn">$29.99</td>
                <td class="product-7thcolumn">$9.84</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn"></td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A0</td>
                <td class="product-fifthcolumn">$20.15</td>
                <td class="product-fifthcolumn">$29.99</td>
                <td class="product-7thcolumn">$9.84</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle36.png') }}"></td>
                <td class="product-secondcolumn">A1-PSTR-KZ</td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A1</td>
                <td class="product-fifthcolumn">$12.44</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$7.55</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle37.png') }}"></td>
                <td class="product-secondcolumn"></td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A1</td>
                <td class="product-fifthcolumn">$12.44</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$7.55</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle38.png') }}"></td>
                <td class="product-secondcolumn">A2-PSTR-KZ</td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A2</td>
                <td class="product-fifthcolumn">$9.55</td>
                <td class="product-fifthcolumn">$16.99</td>
                <td class="product-7thcolumn">$7.44</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle39.png') }}"></td>
                <td class="product-secondcolumn"></td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A2</td>
                <td class="product-fifthcolumn">$9.55</td>
                <td class="product-fifthcolumn">$16.99</td>
                <td class="product-7thcolumn">$7.44</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle40.png') }}"></td>
                <td class="product-secondcolumn">A3-PSTR-KZ</td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A3</td>
                <td class="product-fifthcolumn">$7.77</td>
                <td class="product-fifthcolumn">$14.99</td>
                <td class="product-7thcolumn">$7.22</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle41.png') }}"></td>
                <td class="product-secondcolumn"></td>
                <td class="product-thirdcolumn">ART PRINT</td>
                <td class="product-thirdcolumn">A3</td>
                <td class="product-fifthcolumn">$7.77</td>
                <td class="product-fifthcolumn">$14.99</td>
                <td class="product-7thcolumn">$7.22</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Phone Cases</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle42.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Shopify - iphone 6</td>
                <td class="product-thirdcolumn">Shopify - iPhone 6</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle43.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle44.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7 Plus</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7 Plus</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle45.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7 Plus</td>
                <td class="product-thirdcolumn">Shopify - iPhone 7 Plus</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Socks</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle47.png') }}"></td>
                <td class="product-secondcolumn">socks</td>
                <td class="product-thirdcolumn">Socks</td>
                <td class="product-thirdcolumn">7"</td>
                <td class="product-fifthcolumn">$13.92</td>
                <td class="product-fifthcolumn">$17.99</td>
                <td class="product-7thcolumn">$4.07</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle48.png') }}"></td>
                <td class="product-secondcolumn">socks</td>
                <td class="product-thirdcolumn">Socks</td>
                <td class="product-thirdcolumn">11"</td>
                <td class="product-fifthcolumn">$14.73</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$5.26</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Towels</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle46.png') }}"></td>
                <td class="product-secondcolumn">S-TWL</td>
                <td class="product-thirdcolumn">S-Towel</td>
                <td class="product-thirdcolumn">S-Towel</td>
                <td class="product-fifthcolumn">$20.50</td>
                <td class="product-fifthcolumn">$3.99</td>
                <td class="product-7thcolumn">$13.49</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-12 col-xs-12">
                        <p>Sizes: 10x10 / 15x24</p>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Stickers</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle49.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">sticker</td>
                <td class="product-thirdcolumn">sticker</td>
                <td class="product-fifthcolumn">$1.99</td>
                <td class="product-fifthcolumn">$3.99</td>
                <td class="product-7thcolumn">$2.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle50.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Shopfiy - sticker</td>
                <td class="product-thirdcolumn">Shopfiy - sticker</td>
                <td class="product-fifthcolumn">$1.99</td>
                <td class="product-fifthcolumn">$3.99</td>
                <td class="product-7thcolumn">$2.00</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Headwear</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle51.png') }}"></td>
                <td class="product-secondcolumn">1501</td>
                <td class="product-thirdcolumn">Beanie</td>
                <td class="product-thirdcolumn">Beanie</td>
                <td class="product-fifthcolumn">$16.00</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$3.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Sizes: OS</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #018264" data-toggle="tooltip" data-placement="top" title="" data-original-title="Kelly"></div>
                            <div class="colorbox" style="background: #1541aa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Royal"></div>
                            <div class="colorbox" style="background: #1b174d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Navy"></div>
                            <div class="colorbox" style="background: #21217a" data-toggle="tooltip" data-placement="top" title="" data-original-title="NAVY"></div>
                            <div class="colorbox" style="background: #2e4227" data-toggle="tooltip" data-placement="top" title="" data-original-title="Olive"></div>
                            <div class="colorbox" style="background: #302f2f" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #353434" data-toggle="tooltip" data-placement="top" title="" data-original-title="BLACK"></div>
                            <div class="colorbox" style="background: #483131" data-toggle="tooltip" data-placement="top" title="" data-original-title="Brown"></div>
                            <div class="colorbox" style="background: #532c61" data-toggle="tooltip" data-placement="top" title="" data-original-title="PURPLE"></div>
                            <div class="colorbox" style="background: #5f595b" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Grey"></div>
                            <div class="colorbox" style="background: #6c1f3f" data-toggle="tooltip" data-placement="top" title="" data-original-title="Maroon"></div>
                            <div class="colorbox" style="background: #7ba4db" data-toggle="tooltip" data-placement="top" title="" data-original-title="CAROLINA BLUE"></div>
                            <div class="colorbox" style="background: #B3BCCC" data-toggle="tooltip" data-placement="top" title="" data-original-title="Heather"></div>
                            <div class="colorbox" style="background: #c01c34" data-toggle="tooltip" data-placement="top" title="" data-original-title="Red"></div>
                            <div class="colorbox" style="background: #da662d" data-toggle="tooltip" data-placement="top" title="" data-original-title="Orange"></div>
                            <div class="colorbox" style="background: #fbb6c8" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pink"></div>
                            <div class="colorbox" style="background: #fead28" data-toggle="tooltip" data-placement="top" title="" data-original-title="Gold"></div>
                            <div class="colorbox" style="background: #FFFFFF" data-toggle="tooltip" data-placement="top" title="" data-original-title="WHITE"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle52.png') }}"></td>
                <td class="product-secondcolumn">6210</td>
                <td class="product-thirdcolumn">Fitted Baseball Cap</td>
                <td class="product-thirdcolumn">Fitted Baseball Cap</td>
                <td class="product-fifthcolumn">$19.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$5.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p>Sizes: S/M / L/XL</p>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #212342" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dark Navy"></div>
                            <div class="colorbox" style="background: #656361" data-toggle="tooltip" data-placement="top" title="" data-original-title="GREY"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle53.png') }}"></td>
                <td class="product-secondcolumn">BX025</td>
                <td class="product-thirdcolumn">Snap Back Trucker Cap</td>
                <td class="product-thirdcolumn">Snap Back Trucker Cap</td>
                <td class="product-fifthcolumn">$16.00</td>
                <td class="product-fifthcolumn">$21.99</td>
                <td class="product-7thcolumn">$5.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-12 col-xs-12">
                        <p>Sizes: OS</p>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle55.png') }}"></td>
                <td class="product-secondcolumn">Y6007-S</td>
                <td class="product-thirdcolumn">Snap Back Baseball Cap</td>
                <td class="product-thirdcolumn">Snap Back Baseball Cap</td>
                <td class="product-fifthcolumn">$19.00</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$5.99</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-12 col-xs-12">
                        <p>Sizes: OS</p>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle56.png') }}"></td>
                <td class="product-secondcolumn">6210-S</td>
                <td class="product-thirdcolumn">Shopify - Fitted Baseball Cap</td>
                <td class="product-thirdcolumn"> Shopify - Fitted Baseball Cap</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle57.png') }}"></td>
                <td class="product-secondcolumn">BX025-S</td>
                <td class="product-thirdcolumn">Shopify - Snap Back Trucker Cap</td>
                <td class="product-thirdcolumn"> Shopify - Snap Back Trucker Cap  </td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle54.png') }}"></td>
                <td class="product-secondcolumn">110F-S</td>
                <td class="product-thirdcolumn">Shopify - Snap Back Baseball Cap</td>
                <td class="product-thirdcolumn">Shopify - Snap Back Baseball Cap</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle58.png') }}"></td>
                <td class="product-secondcolumn">1501-S</td>
                <td class="product-thirdcolumn">Shopify - Beanie</td>
                <td class="product-thirdcolumn">Shopify - Beanie</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-fifthcolumn">$0.00</td>
                <td class="product-7thcolumn">$0.00</td>
                <td class="product-8thcolumn product-colorchange1"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8"></td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle59.png') }}"></td>
                <td class="product-secondcolumn">FleeceBlanket_50x60</td>
                <td class="product-thirdcolumn">Size: 50x60</td>
                <td class="product-thirdcolumn">Fleece Blankets</td>
                <td class="product-fifthcolumn">$37.18</td>
                <td class="product-fifthcolumn">$49.99</td>
                <td class="product-7thcolumn">$12.81</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle60.png') }}"></td>
                <td class="product-secondcolumn">FleeceBlanket_60x80</td>
                <td class="product-thirdcolumn">Size: 60x80</td>
                <td class="product-thirdcolumn">Fleece Blankets</td>
                <td class="product-fifthcolumn">$42.18</td>
                <td class="product-fifthcolumn">$54.99</td>
                <td class="product-7thcolumn">$12.81</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle61.png') }}"></td>
                <td class="product-secondcolumn">WovenBlanket_50x60</td>
                <td class="product-thirdcolumn">Woven Blanket</td>
                <td class="product-thirdcolumn">Woven Blankets</td>
                <td class="product-fifthcolumn">$56.17</td>
                <td class="product-fifthcolumn">$67.99</td>
                <td class="product-7thcolumn">$11.82</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle62.png') }}"></td>
                <td class="product-secondcolumn">EverythingBag</td>
                <td class="product-thirdcolumn">OS Everything Bag</td>
                <td class="product-thirdcolumn">Everything Bags</td>
                <td class="product-fifthcolumn">$19.08</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$5.91</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle63.png') }}"></td>
                <td class="product-secondcolumn">BathTowel-30x60</td>
                <td class="product-thirdcolumn">30"x60" Bath Towel</td>
                <td class="product-thirdcolumn">Bath Towels</td>
                <td class="product-fifthcolumn">$29.56</td>
                <td class="product-fifthcolumn">$39.99</td>
                <td class="product-7thcolumn">$10.43</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle64.png') }}"></td>
                <td class="product-secondcolumn">BeachTowel-36x72</td>
                <td class="product-thirdcolumn">36"x72" Bath Towel</td>
                <td class="product-thirdcolumn">Bath Towels</td>
                <td class="product-fifthcolumn">$34.97</td>
                <td class="product-fifthcolumn">$44.99</td>
                <td class="product-7thcolumn">$10.02</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle65.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Fleece-18x28</td>
                <td class="product-thirdcolumn">Fleece Dog Bed 18"x28"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$32.76</td>
                <td class="product-fifthcolumn">$39.99</td>
                <td class="product-7thcolumn">$7.23</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle66.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Fleece-30x40</td>
                <td class="product-thirdcolumn">Fleece Dog Bed 30"x40"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$43.42</td>
                <td class="product-fifthcolumn">$49.99</td>
                <td class="product-7thcolumn">$6.57</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle67.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Fleece-40x50</td>
                <td class="product-thirdcolumn">Fleece Dog Bed 40"x50"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$70.79</td>
                <td class="product-fifthcolumn">$79.99</td>
                <td class="product-7thcolumn">$9.20</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle68.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Outdoor-18x28</td>
                <td class="product-thirdcolumn">Outdoor Dog Bed 18"x28"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$34.47</td>
                <td class="product-fifthcolumn">$43.99</td>
                <td class="product-7thcolumn">$9.52</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle69.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Outdoor-30x40</td>
                <td class="product-thirdcolumn">Outdoor Dog Bed 30"x40"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$46.79</td>
                <td class="product-fifthcolumn">$55.99</td>
                <td class="product-7thcolumn">$9.20</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle70.png') }}"></td>
                <td class="product-secondcolumn">DogBed-Outdoor-40x50</td>
                <td class="product-thirdcolumn">Outdoor Dog Bed 40"x50"</td>
                <td class="product-thirdcolumn">Dog Beds</td>
                <td class="product-fifthcolumn">$76.77</td>
                <td class="product-fifthcolumn">$94.99</td>
                <td class="product-7thcolumn">$18.22</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle71.png') }}"></td>
                <td class="product-secondcolumn">DuvetCover-K-Luxe-88x104-WhiteBack</td>
                <td class="product-thirdcolumn">88"x104" WhiteBack</td>
                <td class="product-thirdcolumn">Duvet Covers</td>
                <td class="product-fifthcolumn">$145.79</td>
                <td class="product-fifthcolumn">$199.99</td>
                <td class="product-7thcolumn">$54.20</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle72.png') }}"></td>
                <td class="product-secondcolumn">DuvetCover-Q-Luxe-88x88-WhiteBack</td>
                <td class="product-thirdcolumn">88"x88" WhiteBack</td>
                <td class="product-thirdcolumn">Duvet Covers</td>
                <td class="product-fifthcolumn">$127.94</td>
                <td class="product-fifthcolumn">$169.99</td>
                <td class="product-7thcolumn">$42.05   </td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle73.png') }}"></td>
                <td class="product-secondcolumn">DuvetCover-TW-Luxe-68x88-WhiteBack</td>
                <td class="product-thirdcolumn">68"x88" WhiteBack</td>
                <td class="product-thirdcolumn">Duvet Covers</td>
                <td class="product-fifthcolumn">$110.57</td>
                <td class="product-fifthcolumn">$149.99</td>
                <td class="product-7thcolumn">$39.42</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle74.png') }}"></td>
                <td class="product-secondcolumn">Mug-11oz</td>
                <td class="product-thirdcolumn">11oz Mug</td>
                <td class="product-thirdcolumn">Mugs</td>
                <td class="product-fifthcolumn">$20.57</td>
                <td class="product-fifthcolumn">$15.99</td>
                <td class="product-7thcolumn">$-4.58</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn">Ottoman-13x13x13</td>
                <td class="product-thirdcolumn">13x13x13</td>
                <td class="product-thirdcolumn">Ottoman</td>
                <td class="product-fifthcolumn">$60.31</td>
                <td class="product-fifthcolumn">$69.99</td>
                <td class="product-7thcolumn">$9.68</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn">Ottoman-18x18x18</td>
                <td class="product-thirdcolumn">18x18x18</td>
                <td class="product-thirdcolumn">Ottoman</td>
                <td class="product-fifthcolumn">$78.63</td>
                <td class="product-fifthcolumn">$95.05</td>
                <td class="product-7thcolumn">$16.42</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle75.png') }}"></td>
                <td class="product-secondcolumn">ToteBag-13x13</td>
                <td class="product-thirdcolumn">13"x13" Tote Bag</td>
                <td class="product-thirdcolumn">Tote Bags</td>
                <td class="product-fifthcolumn">$15.27</td>
                <td class="product-fifthcolumn">$19.99</td>
                <td class="product-7thcolumn">$4.72</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle76.png') }}"></td>
                <td class="product-secondcolumn">ThrowPillow_Zipper_16x16</td>
                <td class="product-thirdcolumn">16"x16" Throw Pillow</td>
                <td class="product-thirdcolumn">Throw Pillow Zipper</td>
                <td class="product-fifthcolumn">$19.22</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$5.77</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle77.png') }}"></td>
                <td class="product-secondcolumn">ThrowPillow_Zipper_18x18</td>
                <td class="product-thirdcolumn">18"x18" Throw Pillow</td>
                <td class="product-thirdcolumn">Throw Pillow Zipper</td>
                <td class="product-fifthcolumn">$21.36</td>
                <td class="product-fifthcolumn">$27.99</td>
                <td class="product-7thcolumn">$6.63</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle78.png') }}"></td>
                <td class="product-secondcolumn">ThrowPillow_Zipper_20x20</td>
                <td class="product-thirdcolumn">20"x20" Throw Pillow</td>
                <td class="product-thirdcolumn">Throw Pillow Zipper</td>
                <td class="product-fifthcolumn">$26.42</td>
                <td class="product-fifthcolumn">$32.99</td>
                <td class="product-7thcolumn">$6.57</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle79.png') }}"></td>
                <td class="product-secondcolumn">ThrowPillow_Zipper_26x26</td>
                <td class="product-thirdcolumn">26"x26" Throw Pillow</td>
                <td class="product-thirdcolumn">Throw Pillow Zipper</td>
                <td class="product-fifthcolumn">$35.07</td>
                <td class="product-fifthcolumn">$44.99</td>
                <td class="product-7thcolumn">$9.92</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle80.png') }}"></td>
                <td class="product-secondcolumn">ShowerCurtain_71x74</td>
                <td class="product-thirdcolumn">71"x74" Shower Curtain</td>
                <td class="product-thirdcolumn">Shower Curtains</td>
                <td class="product-fifthcolumn">$44.23</td>
                <td class="product-fifthcolumn">$59.99</td>
                <td class="product-7thcolumn">$15.76</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle81.png') }}"></td>
                <td class="product-secondcolumn">Mug-Ceramic-11oz-White</td>
                <td class="product-thirdcolumn">11oz Mug International</td>
                <td class="product-thirdcolumn">Mug-International</td>
                <td class="product-fifthcolumn">$20.57</td>
                <td class="product-fifthcolumn">$15.99</td>
                <td class="product-7thcolumn">$-4.58</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn">PhoneCase-GalaxyNote3-Glossy</td>
                <td class="product-thirdcolumn">Galaxy Note 4</td>
                <td class="product-thirdcolumn">Phone Cases</td>
                <td class="product-fifthcolumn">$17.50</td>
                <td class="product-fifthcolumn">$24.99</td>
                <td class="product-7thcolumn">$7.49</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> </td>
            </tr>
            <tr>
                <td class="kv-grouped-row" colspan="8">Shoes</td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle82.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Converse Hightop</td>
                <td class="product-thirdcolumn">Converse Hightop</td>
                <td class="product-fifthcolumn">$109.00</td>
                <td class="product-fifthcolumn">$134.99</td>
                <td class="product-7thcolumn">$25.99</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p><strong>Sizes:</strong></p>
                        <p>Men’s: 3 / 3.5 / 4 / 4.5 / 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12 / 12.5 / 13</p>
                        <p>Women's: 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12</p>
                        <img src="{{ url('img/catalog/converse_charts.jpg') }}" alt="No image" class="img-responsive">         
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #fff" data-toggle="tooltip" data-placement="top" title="" data-original-title="White"></div>
                            <div class="colorbox" style="background: #000" data-toggle="tooltip" data-placement="top" title="" data-original-title="Black"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Vans Hightop</td>
                <td class="product-thirdcolumn">Vans Hightop</td>
                <td class="product-fifthcolumn">$109.00</td>
                <td class="product-fifthcolumn">$134.28</td>
                <td class="product-7thcolumn">$25.28</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p><strong>Sizes:</strong></p>
                        <p>Men’s: 3 / 3.5 / 4 / 4.5 / 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12 / 12.5 / 13 / 13.5 / 14 / 14.5 / 15 / 15.5 / 16</p>
                        <p>Women's: 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12</p>
                        <img src="{{ url('img/catalog/vanse_charts.jpg') }}" alt="No image" class="img-responsive">               
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #fff" data-toggle="tooltip" data-placement="top" title="" data-original-title="White"></div>
                            <div class="colorbox" style="background: #000" data-toggle="tooltip" data-placement="top" title="" data-original-title="Black"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="product-firstcolumn"><img src="{{ url('img/catalog/product-middle83.png') }}"></td>
                <td class="product-secondcolumn">(not set)</td>
                <td class="product-thirdcolumn">Vans Lowtop</td>
                <td class="product-thirdcolumn">Vans Lowtop</td>
                <td class="product-fifthcolumn">$109.00</td>
                <td class="product-fifthcolumn">$134.99</td>
                <td class="product-7thcolumn">$25.99</td>
                <td class="product-8thcolumn product-colorchange1" title="Expand"> <a href="javascript:void(0);" class="fg-green product-changelink"><i class="fa fa-plus" aria-hidden="true"></i></a></td>
            </tr>
            <tr>
                <td class="productcolor-change" colspan="8">
                    <div class="skip-export">
                    <div class="col-md-8 col-xs-12">
                        <p><strong>Sizes:</strong></p>
                        <p>Men’s: 3 / 3.5 / 4 / 4.5 / 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12 / 12.5 / 13 / 13.5 / 14 / 14.5 / 15 / 15.5 / 16</p>
                        <p>Women's: 5 / 5.5 / 6 / 6.5 / 7 / 7.5 / 8 / 8.5 / 9 / 9.5 / 10 / 10.5 / 11 / 11.5 / 12</p>
                        <img src="{{ url('img/catalog/vanse_charts145694666356d73de75bdb6.jpg') }}" alt="No image" class="img-responsive">       
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <p>Colors:</p>
                        <div class="color-items">
                            <div class="colorbox" style="background: #fff" data-toggle="tooltip" data-placement="top" title="" data-original-title="White"></div>
                            <div class="colorbox" style="background: #000" data-toggle="tooltip" data-placement="top" title="" data-original-title="Black"></div>
                        </div>
                    </div>
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>

@endsection

{{-- Assets / Scripting --}}
@section('head')
 <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
@endsection

@section('footer')
<link rel="stylesheet" href="/css/catalog.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $(".detailsToggle").click(function(){
        $(".productcolor-change").toggle();
        $(this).find('.fa-plus').toggleClass('fa-minus');
        $(".product-8thcolumn").find('.fa-plus').toggleClass('fa-minus');
     
    });
    $(".product-8thcolumn").click(function(){
        $(this).parent().next('tr').find('td.productcolor-change').toggle();
         $(this).find('.fa-plus').toggleClass('fa-minus');
     
    });
     
    $(document).ready(function(){
         $('[data-toggle="tooltip"]').tooltip();   
    });
</script>
@endsection