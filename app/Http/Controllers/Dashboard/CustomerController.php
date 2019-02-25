<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Customer\SaveBillingAddressRequest;

class CustomerController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.dashboard.customer', [
            'user' => auth()->user()
        ]);
    }

    public function saveBillingAddress(SaveBillingAddressRequest $request)
    {
        $user = auth()->user();
        
        $user->billing_address = filter_var($request->get('address'), FILTER_SANITIZE_STRING);
        $user->billing_address_line_2 = filter_var($request->get('address_line_2'), FILTER_SANITIZE_STRING);
        $user->billing_city = filter_var($request->get('city'), FILTER_SANITIZE_STRING);
        $user->billing_state = filter_var($request->get('state'), FILTER_SANITIZE_STRING);
        $user->billing_zip = filter_var($request->get('zip'), FILTER_SANITIZE_STRING);
        $user->billing_country = filter_var($request->get('country'), FILTER_SANITIZE_STRING);
        $user->save();
    }
}
