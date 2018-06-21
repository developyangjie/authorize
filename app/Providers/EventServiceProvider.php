<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Event::listen(StatementPrepared::class, function ($event) {
            $event->statement->setFetchMode(\PDO::FETCH_ASSOC);
        });
        DB::listen(function($query){
            $sql = str_replace("?", "'%s'", $query->sql);
            $sql = vsprintf($sql, $query->bindings);
            $sql .= ',query_time: '.$query->time;
            $log = new Logger('db');
            $log->pushHandler(
                new StreamHandler(
                    storage_path('logs/sql/'.date('Ym').'/'.date('Ymd').'.log'),
                    Logger::INFO
                )
            );
            $log->addInfo($sql);
        });
    }
}
