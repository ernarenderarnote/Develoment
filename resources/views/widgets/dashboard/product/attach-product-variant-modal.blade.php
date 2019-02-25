<attach-product-variant-modal inline-template="true" v-ref:attach-product-variant-modal>
    
    <div
        class="modal fade ta-st"
        id="js-attach-product-variant-modal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="js-attach-product-variant-modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div slot="modal-header" class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                    <h4 class="modal-title"> 
                        @lang('labels.choose_category')
                    </h4>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-xs-12 p-0">
                            <div class="alert alert-warning m-0">
                                @lang('labels.you_are_syncing'): @{{ productVariant ? productVariant.name : null }}
                            </div>
                        </div>
                    </div>
                    <add-product-wizard v-ref:add-product-wizard inline-template="true">
                        @include('widgets.dashboard.product.add-product-wizard')
                    </add-product-wizard>
                </div>
            </div>
        </div>
    </div>
    
</attach-product-variant-modal>
