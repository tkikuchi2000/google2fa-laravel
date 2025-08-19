<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['api'])->prefix('google2fa')->group(function () {
    Route::get('/qr', function ($response) {
        $inlineUrl = Google2FA::getQRCodeInline(
            config('app.name'),
            Auth::user()->email,
            Auth::user()->google2fa_secret,
        );
        return $response->json(["inline" => $inlineUrl]);
    })->name('api.google2fa.qr');
});
