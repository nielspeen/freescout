<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // To avoid MySQL error in packages:
        // "SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes"
        Schema::defaultStringLength(191);

        // Models observers
        \App\Mailbox::observe(\App\Observers\MailboxObserver::class);
        // Eloquent events for this table are not called automatically, so need to be called manually.
        //\App\MailboxUser::observe(\App\Observers\MailboxUserObserver::class);
        \App\Email::observe(\App\Observers\EmailObserver::class);
        \App\User::observe(\App\Observers\UserObserver::class);
        \App\Conversation::observe(\App\Observers\ConversationObserver::class);
        \App\Customer::observe(\App\Observers\CustomerObserver::class);
        \App\Thread::observe(\App\Observers\ThreadObserver::class);
        \App\Attachment::observe(\App\Observers\AttachmentObserver::class);
        \App\Follower::observe(\App\Observers\FollowerObserver::class);
        \Illuminate\Notifications\DatabaseNotification::observe(\App\Observers\DatabaseNotificationObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Forse HTTPS if using CloudFlare "Flexible SSL"
        // https://support.cloudflare.com/hc/en-us/articles/200170416-What-do-the-SSL-options-mean-
        if (\Helper::isHttps()) {
            // $_SERVER['HTTPS'] = 'on';
            // $_SERVER['SERVER_PORT'] = '443';
            $this->app['url']->forceScheme('https');
        }

        // If APP_KEY is not set, redirect to /install.php
        if (!\Config::get('app.key') && !app()->runningInConsole() && !file_exists(storage_path('.installed'))) {
            // Not defined here yet
            //\Artisan::call("freescout:clear-cache");
            redirect(\Helper::getSubdirectory().'/install.php')->send();
        }

    }
}
