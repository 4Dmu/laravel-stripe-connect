<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;

Route::middleware('auth')
    ->prefix('stripe-connect')
    ->name('stripe-connect.')
    ->group(function () {
        Route::get('return', function () {
            $account = Auth::user()->retrieveStripeAccount();

            Auth::user()
                ->setStripeAccountStatus($account->details_submitted)
                ->save();

            return Route::has(Config::get('stripe_connect.routes.complete'))
                ? Response::redirectToRoute(Config::get('stripe_connect.routes.complete'))
                : Response::redirectTo('/');
        })->name('return');

        Route::get('refresh', function () {
            return Response::redirectTo(Auth::user()->getStripeAccountLink());
        })->name('refresh');
});
