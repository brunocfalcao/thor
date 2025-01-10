<?php

namespace Nidavellir\Thor\Concerns\User;

use Nidavellir\Thor\Notifications\PushoverNotification;

trait HasNotificationFeatures
{
    /**
     * Send a Pushover notification to this user.
     *
     * @param  string  $title  The notification title.
     * @param  string  $message  The notification message.
     * @param  array  $additionalParameters  Additional notification data (e.g., priority, sound, html).
     * @param  string|null  $applicationKey  The application token key from the config (default: 'nidavellir').
     * @return bool|string True if successful, or error message on failure.
     */
    public function pushover(string $message, string $title = 'Nidavellir message', ?string $applicationKey = 'nidavellir', array $additionalParameters = [])
    {
        // Ensure the user has a valid pushover key.
        if (! $this->pushover_key) {
            return 'User does not have a Pushover key.';
        }

        try {
            // Create and send the notification
            $notification = new PushoverNotification($message, $applicationKey, $title, $additionalParameters);
            $notification->send($this);

            return true;
        } catch (\Exception $e) {
            return 'Failed to send notification: '.$e->getMessage();
        }
    }
}
