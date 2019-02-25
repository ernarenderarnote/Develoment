<?php

namespace App\Providers;

use Spark;
use DateTime;
use Blade;
use Queue;
use RuntimeException;   
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Queue::failing(function (JobFailed $event) {
            Bugsnag::registerCallback(function ($report) use($event) {
                $report->setMetaData([
                    'event' => $event
                ]);
            });
            Bugsnag::notifyException(new Exception('JobFailed'));
        });

        Spark::swap('SendSupportEmail@handle', function (array $data) {
            $supportRequest = new SupportRequest();
            $supportRequest->openTicket([
                'subject' => $data['subject'],
                'text' => $data['message']
            ],
            [
                'from' => $data['from']
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
        // blade extensions
            Blade::directive('app_date', function($at) {
                return '
                <?php
                    $_dt = (new DateTime('.$at.', \App\Models\Base::getDateTimeZone()));
                    $_dt->setTimezone(new DateTimeZone("'.config('app.timezone').'"));
                    echo $_dt->format("F j, Y");
                ?>
                ';
            });

            Blade::directive('app_time', function($at) {
                return '
                <?php
                    $_dt = (new DateTime('.$at.', \App\Models\Base::getDateTimeZone()));
                    $_dt->setTimezone(new DateTimeZone("'.config('app.timezone').'"));
                    echo $_dt->format("h:ia");
                ?>
                ';
            });

            Blade::directive('app_time_tag', function($at) {
                return '
                <?php
                    $_dt = (new DateTime('.$at.', \App\Models\Base::getDateTimeZone()));
                    $_dt->setTimezone(new DateTimeZone("'.config('app.timezone').'"));
                    $time = $_dt->format(DateTime::W3C);
                ?>
                <time
                    datetime="<?php echo $time ?>"
                    title="<?php echo $time ?>"
                    data-app-time="<?php echo $_dt->format("F j, Y h:ia") ?>"
                    data-format="">
                    <?php echo $time ?>
                </time>';
            });

            Blade::directive('price', function($value) {
                return '
                <?php
                    echo \App\Components\Money::i()->format('.$value.');
                ?>
                ';
            });
    }
}
