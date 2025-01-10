<?php

use Illuminate\Support\Facades\Notification;
use Nidavellir\Thor\Models\User;
use Nidavellir\Thor\Notifications\NotifyAppEvent;
use Nidavellir\Thor\Support\NotificationMessage;

if (! function_exists('notify')) {
    function notify(
        ?string $title = null,
        ?string $message = null,
        string $sound = 'pushover',
        array $url = [],
        string $application = 'nidavellir',
        ?User $user = null
    ) {
        // Check user.
        if (! $user) {
            $notifiable = User::firstWhere('is_admin', true);
        } else {
            $notifiable = $user;
        }

        if ($notifiable) {
            $notifiable->pushover_application_key = config(
                "nidavellir.apis.pushover.{$application}"
            );

            if ($title == null) {
                $title = $message;
            } elseif ($message == null) {
                $message = $title;
            }

            $message = new NotificationMessage(
                $message,
                $title,
                $sound,
                ['url' => $url[0] ?? '', 'text' => $url[1] ?? '']
            );

            Notification::send($notifiable, new NotifyAppEvent($message));
        }
    }
}
