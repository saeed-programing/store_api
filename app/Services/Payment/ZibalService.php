<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class ZibalService
{
    private string $merchant;
    private string $callbackUrl;

    public function __construct()
    {
        $this->merchant = config('services.zibal_payment.merchant');
        $this->callbackUrl = config('services.zibal_payment.callback_url');
    }

    public function send(int $amount)
    {
        return Http::asJson()->post('https://gateway.zibal.ir/v1/request', [
            'merchant' => $this->merchant,
            'amount' => $amount,
            'callbackUrl' => $this->callbackUrl,
        ])->json();
    }
    public function verify(string $token)
    {
        return Http::asJson()->post('https://gateway.zibal.ir/v1/verify', [
            'merchant' => $this->merchant,
            'trackId' => $token,
        ])->json();
    }
}
