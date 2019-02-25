<div
	class="modal fade"
	id="js-create-store-modal"
	tabindex="-1"
	role="dialog"
	aria-labelledby="js-create-store-modal-title"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<span class="fa fa-times"></span>
				</button>
				<h4 class="modal-title" id="js-create-store-modal-title">

				</h4>
			</div>
			<div class="modal-body">

                <div class="row">
                    {!! BootForm::open([])
                        ->action('/dashboard/store/create')
                        ->addClass('ta-c p-30 mb-10')
                        ->post() !!}

                        <h3 class="mt-35">@lang('labels.create_custom_store')</h3>

                        {!! BootForm::text(trans('labels.store_name'), 'name')
                            ->hideLabel()
                            ->attribute('required', 'required') !!}

                        {!!
                            BootForm::submit(trans('actions.create'))
                                ->attribute('class', 'btn btn-danger')
                        !!}
                    {!! BootForm::close() !!}

                </div>

			</div>
		</div>
	</div>
</div>
