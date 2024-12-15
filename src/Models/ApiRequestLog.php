<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    protected $table = 'api_requests_log';

    protected $casts = [
        'debug_data' => 'array',
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
