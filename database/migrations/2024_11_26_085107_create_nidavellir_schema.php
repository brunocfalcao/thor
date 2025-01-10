<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // FK'S BOOLEANS INTS/NUMERICS STRINGS ARRAYS/JSONS TEXTS DATETIMES
    public function up(): void
    {
        Schema::create('price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exchange_symbol_id');
            $table->decimal('mark_price', 20, 8)->nullable();
            $table->timestamps();
        });

        Schema::create('core_job_queue', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('sequencial_id')->nullable();
            $table->unsignedInteger('index')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedInteger('retries')->default(0);

            $table->string('class');
            $table->string('status')->default('pending');
            $table->string('queue')->nullable();
            $table->string('hostname')->nullable();
            $table->string('canonical')->nullable();

            $table->uuid('block_uuid')->nullable();
            $table->uuid('job_uuid')->nullable();

            $table->json('arguments')->nullable();
            $table->json('response')->nullable();

            $table->text('error_message')->nullable();
            $table->text('error_stack_trace')->nullable();

            $table->timestamp('dispatch_after')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });

        Schema::create('indicators', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_active')->default(true);

            $table->boolean('is_apiable')->default(true)
                ->comment('Indicator will call the api or not');

            $table->string('canonical')
                ->unique()
                ->comment('Internal id that will be set on the indicator class for further results mapping');

            $table->json('parameters')
                ->nullable()
                ->comment('The parameters that will be passed to taapi. Just indicator parameters, not secret neither exchange parameters');

            $table->string('class')
                ->comment('The indicator class that will be used to instance and use the indicator');

            $table->timestamps();
        });

        Schema::create('trade_configuration', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_default')->default(1);

            $table->string('direction_priority')
                ->nullable()
                ->comment('This direction will be the priority to select the next tokens, in case they exist. This will allow the bot to select the wrong direction even if the conclusion is the good one');

            $table->string('canonical')->unique();
            $table->text('order_ratios');
            $table->decimal('profit_percentage', 5, 3);
            $table->json('indicator_timeframes')->nullable()
                ->comment('Indicator timeframes considered for the trade configuration');

            $table->longText('description');

            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->string('canonical')->unique();
            $table->string('name');

            $table->timestamps();
        });

        Schema::create('trading_pairs', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('cmc_id');
            $table->string('token')->unique();
            $table->json('exchange_canonicals')->nullable();
            $table->string('category_canonical');

            $table->timestamps();
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('api_system_id');
            $table->foreignId('quote_id')
                ->nullable()
                ->comment('The quote currency used to trade, needs to have a portfolio amount');

            $table->boolean('is_active')
                ->default(true)
                ->comment('Global configuration to allow this account to trade. If the subscription is over, this attribute is false');

            $table->boolean('can_trade')
                ->default(true)
                ->comment('This is used by the system, to temporarly stop trades on this account. E.g.: If a drop is more than 3.5 percent, other framework conditions, etc');

            $table->boolean('follow_btc_indicator')
                ->default(false)
                ->comment('If true, then it will give priority to select tokens aligned with the BTC indicator result from the exchange symbols');

            $table->unsignedInteger('max_concurrent_trades')->nullable();
            $table->unsignedBigInteger('minimum_balance')->nullable()
                ->comment('The minimum available balance to open a new position');

            $table->decimal('position_size_percentage', 5, 2)->nullable()
                ->comment('The margin percentage that will be used on each position, without leverage');

            $table->unsignedInteger('max_margin_ratio')->nullable()
                ->comment('The max leverage that the position can use, inside the leverage bracket amount');

            $table->decimal('negative_pnl_stop_threshold', 5, 2)->nullable()
                ->comment('How much percentage the account can have a negative PnL, before the system stops opening new positions');

            $table->decimal('monthly_profit_objective', 8, 2)
                ->nullable()
                ->comment('The system will try to match this profit objective, and in case it is exceed it drops the trade percentage proportionally. If it doesnt achieve, it keeps the active trading configuration. Each week this can be adjusted by the system');

            $table->unsignedInteger('max_balance_percentage')
                ->nullable()
                ->comment('This is the maximum allowed portfolio percentage for the account');

            $table->json('credentials')
                ->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('api_system_id');
            $table->foreignId('account_id');

            $table->string('hostname');

            $table->datetime('retry_after')->nullable();

            $table->timestamps();

            $table->unique(['hostname', 'account_id', 'api_system_id']);
        });

        Schema::create('traceables', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('relatable_id')->nullable();
            $table->string('relatable_type')->nullable();

            $table->decimal('duration', 10, 5)->nullable();
            $table->decimal('total_duration', 10, 5)->nullable();

            $table->string('class')->nullable();
            $table->longText('arguments')->nullable();
            $table->string('status')->nullable();
            $table->uuid('group_uuid')->nullable();
            $table->string('canonical')->nullable();
            $table->text('description')->nullable();
            $table->text('error_message')->nullable();
            $table->string('hostname')->nullable();

            $table->timestamps();

            $table->index(['relatable_id', 'relatable_type']);
        });

        Schema::create('api_requests_log', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('loggable_id')->nullable();
            $table->integer('http_response_code')->nullable();

            $table->json('debug_data')->nullable();

            $table->string('loggable_type')->nullable();
            $table->string('path')->nullable();
            $table->longText('payload')->nullable();
            $table->string('http_method')->nullable();
            $table->longText('http_headers_sent')->nullable();
            $table->longText('response')->nullable();
            $table->longText('http_headers_returned')->nullable();
            $table->string('hostname')->nullable();
            $table->longText('error_message')->nullable();

            $table->timestamps();

            $table->index(['loggable_id', 'loggable_type']);
            $table->index(['http_response_code']);
        });

        Schema::create('api_systems', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_exchange')->default(true);

            $table->string('name');

            $table->unsignedInteger('recvwindow_margin')
                ->default(10000)
                ->comment('The miliseconds margin so we dont get errors due to server time vs exchange time desynchronizations');

            $table->string('canonical')->unique();
            $table->string('taapi_canonical')->nullable();

            $table->timestamps();
        });

        Schema::create('application_logs', function (Blueprint $table) {
            $table->id();
            $table->string('block')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('api_system_id')->nullable();
            $table->foreignId('exchange_symbol_id')->nullable();
            $table->foreignId('symbol_id')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->string('action_canonical')->nullable();
            $table->text('description')->nullable();
            $table->text('return_value')->nullable();
            $table->longText('return_data')->nullable();
            $table->text('comments')->nullable();
            $table->longText('debug_backtrace')->nullable();
            $table->timestamps();

            $table->index(['block']);
            $table->index(['user_id', 'api_system_id']);
            $table->index(['exchange_symbol_id', 'symbol_id']);
        });

        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cmc_id')->nullable();
            $table->string('token')->unique();
            $table->string('name')->nullable();
            $table->string('category_canonical');
            $table->string('category')->nullable();
            $table->string('website')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_url')->nullable();
            $table->json('exchange_canonicals')
                ->nullable()
                ->comment('In case the symbol canonical differs from exchange to exchange, we can use it here canonical - token name');

            $table->timestamps();
        });

        Schema::create('exchange_symbols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id');
            $table->foreignId('quote_id');
            $table->foreignId('trade_configuration_id');

            $table->boolean('is_active')
                ->default(true)
                ->comment('Global active status exchange symbol. If false, it will not be selected for trades, no matter else');

            $table->boolean('is_upsertable')
                ->default(true)
                ->comment('If this exchange symbol will be updated (cronjobs, last mark price, etc)');

            $table->boolean('is_tradeable')
                ->default(false)
                ->comment('If this exchange symbol will be available for new positions');

            $table->string('direction')
                ->nullable()
                ->comment('The exchange symbol open position direction (LONG, SHORT)');

            $table->unsignedInteger('api_system_id');
            $table->unsignedInteger('price_precision');
            $table->unsignedInteger('quantity_precision');
            $table->decimal('min_notional', 20, 8)->nullable()
                ->comment('The minimum position size that can be opened (quantity x price at the moment of the position opening)');

            $table->decimal('tick_size', 20, 8);
            $table->longText('symbol_information')->nullable();
            $table->longText('leverage_brackets')->nullable();
            $table->decimal('last_mark_price', 20, 8)->nullable();
            $table->text('indicators')->nullable();
            $table->string('indicator_timeframe')->nullable();
            $table->timestamp('indicators_last_synced_at')->nullable();
            $table->timestamp('price_last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['symbol_id', 'api_system_id', 'quote_id']);
            $table->index('last_mark_price');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified_at',
                'email',
                'password',
                'created_at',
                'updated_at',
            ]);

            $table->string('name')->nullable()->change();
            $table->timestamp('previous_logged_in_at')->nullable()->after('remember_token');
            $table->timestamp('last_logged_in_at')->nullable()->after('previous_logged_in_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
            $table->string('password')->nullable()->after('email');

            $table->boolean('is_active')
                ->default(false);

            $table->boolean('is_admin')
                ->default(false);

            $table->text('pushover_key')->nullable()->after('password');
            $table->boolean('is_trader')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id');

            $table->uuid();

            $table->string('type')
                ->comment('PROFIT, MARKET, LIMIT, CANCEL-MARKET');

            $table->string('status')
                ->default('NEW')
                ->nullable()
                ->comment('The order status (filled, cancelled, new, etc)');

            $table->string('side')
                ->comment('BUY or SELL - To open a short, or a long');

            $table->string('exchange_order_id')
                ->nullable()
                ->comment('The exchange system order id');

            $table->decimal('quantity', 20, 8)
                ->nullable()
                ->comment('The order initial or filled quantity, depending on the order status');

            $table->decimal('price', 20, 8)
                ->nullable()
                ->comment('The order initial or average price, depending on the status');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->longText('api_result')->nullable();
            $table->longText('error_message')->nullable();
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id');

            $table->foreignId('exchange_symbol_id')->nullable();

            $table->string('status')->default('new')
                ->comment('The position status: new (never synced/syncing), active (totally synced), closed (synced, but no longer active), cancelled (there was an error or was compulsively cancelled)');

            $table->string('direction')->nullable()
                ->comment('The position direction: LONG, or SHORT');

            $table->uuid();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->boolean('wap_triggered')
                ->default(false)
                ->comment('If a WAP was just triggered this will be true, allowing the order observer not to put back the profit order on its previous price and quantity');

            $table->decimal('opening_price', 20, 8)
                ->nullable()
                ->comment('The current exchange symbol mark price when the position was opened');

            $table->decimal('closing_price', 20, 8)
                ->nullable()
                ->comment('The last profit price');

            $table->decimal('realized_pnl', 20, 8)
                ->nullable()
                ->comment('The realized PnL given by the exchange api');

            $table->longText('order_ratios')->nullable()
                ->comment('The trade configuration (profit, limit, market, etc)');

            $table->decimal('margin', 20, 8)->nullable()
                ->comment('The position margin (meaning the portfolio amount without leverage)');

            $table->unsignedTinyInteger('leverage')->nullable();

            $table->decimal('profit_percentage', 6, 3)->nullable()
                ->comment('The profit percentage obtained from the trade configuration');

            $table->text('error_message')->nullable();

            $table->text('comments')->nullable();

            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\SchemaSeeder1::class,
        ]);

        Artisan::call('db:seed', [
            '--class' => Nidavellir\Thor\Database\Seeders\TestingSeeder::class,
        ]);
    }
};
