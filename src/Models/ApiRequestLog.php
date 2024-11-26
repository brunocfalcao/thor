<?php

namespace Nidavellir\Thor\Models;

use Nidavellir\Thor\Abstracts\UnguardableModel;

class ApiRequestLog extends UnguardableModel
{
    protected $table = 'api_requests_log';

    protected $casts = [
        'payload' => 'array',
        'http_headers_sent' => 'array',
        'response' => 'array',
        'http_headers_returned' => 'array',
    ];

    public function loggable()
    {
        return $this->morphTo();
    }
}
