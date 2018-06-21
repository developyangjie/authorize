<?php

namespace App\Providers;

use App\Services\DecryptXml;
use App\Services\Fans;
use App\Services\Material;
use App\Services\User;
use Illuminate\Support\ServiceProvider;

use App\Services\Authorize;
use App\Services\Wx;
use App\Services\WxListen;
use App\Services\CheckSign;
use App\Services\Sms;
use App\Services\Msg;

use App\Http\Lib\WX\JSSDK;
use App\Http\Lib\WX\DecryptMsg\WXBizMsgCrypt;



class AuthorizeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Authorize', Authorize::class);
        $this->app->singleton('JSSDK', JSSDK::class);
        $this->app->singleton('Wx', Wx::class);
        $this->app->singleton('WxListen', WxListen::class);
        $this->app->singleton('CheckSign', CheckSign::class);
        $this->app->singleton('Sms', Sms::class);
        $this->app->singleton('Msg', Msg::class);
        $this->app->singleton('User', User::class);
        $this->app->singleton('DecryptXml', DecryptXml::class);
        $this->app->singleton('Material', Material::class);
        $this->app->singleton('Fans', Fans::class);
    }
}
