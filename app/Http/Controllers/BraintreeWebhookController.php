<?php

namespace App\Http\Controllers;

use Input;
use Exception;
use Bugsnag;
use Braintree_WebhookNotification;

use Illuminate\Http\Request;

use App\Components\Logger;

class BraintreeWebhookController extends Controller
{
    /**
     * Webhook endpoint
     */
    public function webhook(Request $request)
    {
        Logger::i(Logger::WEBHOOK_BRAINTREE)->notice('------------------------');

		if(
			!$request->get('bt_signature')
			|| !$request->get('bt_payload')
		) {
            Logger::i(Logger::WEBHOOK_BRAINTREE)->notice('bt_signature or bt_payload is empty');
			return abort(400, 'bt_signature or bt_payload is empty');
		}

        // we will log all webhooks
        Logger::i(Logger::WEBHOOK_BRAINTREE)->notice('Request content:');
        Logger::i(Logger::WEBHOOK_BRAINTREE)->notice($request->getContent());

        try {
            $webhookNotification = Braintree_WebhookNotification::parse(
                $request->get('bt_signature'), $request->get('bt_payload')
            );
        }
        catch(Exception $e) {
            Logger::i(Logger::WEBHOOK_BRAINTREE)->notice('Webhook parse error: '.$e->getMessage(), [
                'exception' => $e
            ]);
            Bugsnag::notifyException($e);
            return abort(400, 'Webhook parse error');
        }

		// $log =
		// 	"[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
		// 	. "Kind: " . $webhookNotification->kind . "  "
		// 	. "\n";
        Logger::i(Logger::WEBHOOK_BRAINTREE)->notice('Webhook Notification', [
            '$webhookNotification' => $webhookNotification
        ]);

		switch ($webhookNotification->kind) {

        }

        return [];
    }
}
