<?php

namespace App\Http\Controllers;

use App\Http\Requests\PayOSPaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayOSController
{
    public function createPayment(PayOSPaymentRequest $request): JsonResponse
    {
        $amount = (int) $request->input('amount');
        $amount = ($amount < 1000 || $amount > 2000) ? 2000 : $amount;

        $cancelUrl = $request->input('cancelUrl', '');
        $returnUrl = $request->input('returnUrl', '');

        $orderCode = (int)(preg_replace('/\D/', '', substr($request->input('orderCode'), 0, 7)) . rand(1, 100000));
        $description = $request->input('description', 'THANH TOAN CHUYEN KHOAN');
        $signingString = "amount={$amount}&cancelUrl={$cancelUrl}&description={$description}&orderCode={$orderCode}&returnUrl={$returnUrl}";
        $signature = hash_hmac('sha256', $signingString, config('payos.checksum_key'));

        $payload = [
            'orderCode' => $orderCode,
            'amount' => $amount,
            'description' => $description,
            'buyerName' => $request->input('name'),
            'buyerEmail' => $request->input('email'),
            'buyerPhone' => $request->input('phone'),
            'buyerAddress' => $request->input('address'),
            'items' => $request->input('items', []),
            'cancelUrl' => $cancelUrl,
            'returnUrl' => $returnUrl,
            'expiredAt' => $request->input('expiredAt', now()->addMinutes(10)->timestamp),
            'signature' => $signature,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-client-id' => config('payos.client_id'),
            'x-api-key' => config('payos.api_key'),
        ])->post(config('payos.api_url') . '/v2/payment-requests', $payload);

        return response()->json($response->json(), $response->status());
    }

}
