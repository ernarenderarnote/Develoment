<?php

namespace App\Models;

use Laravel\Spark\Notification as BaseNotification;

class Notification extends BaseNotification
{
    const TYPE_DEFAULT = '';
    const TYPE_ANNOUNCEMENT = 'announcement';
}
