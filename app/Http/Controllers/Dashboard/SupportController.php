<?php

namespace App\Http\Controllers\Dashboard;

use Gate;

use App\Http\Requests\Dashboard\Support\OpenTicketFormRequest;
use App\Http\Controllers\Controller;
use App\Models\SupportRequest;

class SupportController extends Controller
{

    /**
     * Create ticket
     */
    public function openTicket(OpenTicketFormRequest $request)
    {
        $supportRequest = new SupportRequest();
        if (Gate::denies('openTicket', $supportRequest)) {
            return response()->apiError(
                trans('messages.not_authorized_to_create_tickets'),
                403
            );
        }
        
        $subject = filter_var($request->get('subject'), FILTER_SANITIZE_STRING);
        $text = filter_var($request->get('text'), FILTER_SANITIZE_STRING);
        
		$supportRequest->openTicket([
			'subject' => $subject,
			'text' => $text
		]);

        return response()->api(
            null,
            [
                'ticket' => $supportRequest->transformFull()
            ]
        );
    }
}
