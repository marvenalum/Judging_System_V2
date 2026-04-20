<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Services\NotificationService;
use App\Models\Event;

$judge = User::where('role', 'judge')->first();
if($judge) {
    $event = Event::first();
    if($event) {
        NotificationService::notifyJudgeAssignment($judge, $event);
        echo 'Notification sent!' . PHP_EOL;
    } else {
        echo 'No event found' . PHP_EOL;
    }
} else {
    echo 'No judge found' . PHP_EOL;
}