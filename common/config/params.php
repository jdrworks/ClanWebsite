<?php

use Carbon\Carbon;
$now = Carbon::now();
$reset = new Carbon('Tuesday 04:04', 'utc');

if ($now->greaterThan($reset)) {
    $reset->addUnit('week', 1);
}

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'citadelReset' => $reset,
];
