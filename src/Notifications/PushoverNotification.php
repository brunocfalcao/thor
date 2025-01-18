<?php

namespace Nidavellir\Thor\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class PushoverNotification extends Notification
{
    use Queueable;

    /**
     * The message content.
     *
     * @var string
     */
    protected $message;

    /**
     * The title of the notification.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Additional data for the notification.
     *
     * @var array
     */
    protected $additionalData;

    /**
     * The application token key from the config.
     *
     * @var string
     */
    protected $applicationKey;

    /**
     * Create a new notification instance.
     *
     * @param  string  $applicationKey  The key to fetch the application token from the config.
     */
    public function __construct(string $message, string $applicationKey, ?string $title = null, array $additionalData = [])
    {
        $this->message = $message;
        $this->title = $title;
        $this->additionalData = $additionalData;
        $this->applicationKey = $applicationKey;
    }

    /**
     * Send the notification via Pushover.
     *
     * @param  mixed  $notifiable
     * @return void
     *
     * @throws \Exception
     */
    public function send($notifiable)
    {
        // Ensure the notifiable has a valid pushover key.
        if (! isset($notifiable->pushover_key)) {
            throw new \Exception('The notifiable does not have a Pushover user key.');
        }

        $pushoverUserKey = $notifiable->pushover_key;

        // Get the application token from the configuration.
        $token = config("nidavellir.apis.pushover.{$this->applicationKey}");

        if (! $token) {
            throw new \Exception("Pushover application token '{$this->applicationKey}' is not configured.");
        }

        // Prepare the payload.
        $payload = array_merge(
            [
                'token' => $token,
                'user' => $pushoverUserKey,
                'message' => $this->message,
                'title' => $this->title,
            ],
            $this->additionalData
        );

        // Make the HTTP request to the Pushover API.
        $response = Http::asForm()->post('https://api.pushover.net/1/messages.json', $payload);

        // Check if the request was successful.
        if ($response->failed()) {
            throw new \Exception('Failed to send Pushover notification: '.$response->body());
        }
    }
}
