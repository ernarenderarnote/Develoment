<div class="history">
    <table class="table table-history">
        <tbody>
            <?php $lastDate = null; ?>
            @foreach($order->statusRevisionHistory as $history)
                <?php $currentDate = (new DateTime($history->updated_at, \App\Models\Base::getDateTimeZone()))->format("Y-m-d"); ?>

                <tr>
                    <td width="160">
                        @if ($currentDate != $lastDate)
                            <strong>@app_date($history->updated_at)</strong>
                            <?php $lastDate = $currentDate; ?>
                        @endif
                    </td>
                    <td width="75">
                        <p class="m-0 text-muted">@app_time($history->updated_at)</p>
                    </td>
                    <td>
                        <p class="m-0 text-muted">
                            {{ $history->old_value }}
                            <i class="fa fa-arrow-right"></i>
                            {{ $history->new_value }}
                        </p>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td width="160">
                    <strong>@app_date($order->created_at)</strong>
                </td>
                <td width="75">
                    <p class="m-0 text-muted">@app_time($order->created_at)</p>
                </td>
                <td>
                    <p class="m-0 text-muted">
                        <i class="fa fa-arrow-right"></i>
                        @lang('labels.created')
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>
