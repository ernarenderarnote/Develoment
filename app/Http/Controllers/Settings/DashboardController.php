<?php

namespace App\Http\Controllers\Settings;


class DashboardController extends \Laravel\Spark\Http\Controllers\Settings\DashboardController
{
    /**
     * Show the settings dashboard.
     *
     * @return Response
     */
    public function show()
    {
        return view('dashboard.settings', [
            'firstConnect' => session('firstConnect'),
            'tour' => session('tour')
        ]);

    }
}
