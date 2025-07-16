<?php

// namespace App\Providers;

// use Illuminate\Auth\Events\Login;
// use Illuminate\Auth\Events\Logout;
// use Illuminate\Support\Facades\Event;
// use Illuminate\Support\ServiceProvider;
// use Spatie\Activitylog\Models\Activity;

// class AuthEventServiceProvider extends ServiceProvider
// {
//     public function boot(): void
//     {
//         Event::listen(Login::class, function ($event) {
//             activity()
//                 ->causedBy($event->user)
//                 ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
//                 ->log('Admin logged in');
//         });

//         Event::listen(Logout::class, function ($event) {
//             activity()
//                 ->causedBy($event->user)
//                 ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
//                 ->log('Admin logged out');
//         });
//     }
// }
