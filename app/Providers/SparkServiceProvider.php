<?php

namespace App\Providers;

use Laravel\Spark\Spark;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Printable',
        'product' => 'Printable',
        'street' => '',
        'location' => '',
        'phone' => '',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = null;

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        //
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = true;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */

    public function __construct($app)
    {
        parent::__construct($app);

        $this->developers = explode(',', getenv('ADMIN_USERNAMES'));

        $this->sendSupportEmailsTo = getenv('EMAIL_SUPPORT');
        
    } 

    public function booted()
    {
        Spark::afterLoginRedirectTo('/dashboard');

        Spark::useBraintree()
            ->noCardUpFront()
            ->trialDays(0);
        
        Spark::freePlan()
            ->features([
                'First', 'Second', 'Third'
            ]);

        Spark::plan('Basic', 'provider-id-1')
            ->price(10)
            ->features([
                'First', 'Second', 'Third'
            ]);
    }

    public function register()
    {
        Spark::useUserModel('App\Models\User');
        //Spark::useTeamModel('App\Models\Team');
    }
}
