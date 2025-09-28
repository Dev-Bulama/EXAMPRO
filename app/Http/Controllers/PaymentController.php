<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PricingPlanRepository;
use App\Repositories\TransactionRepository;
use App\Support\Request;
use App\Support\Response;

final class PaymentController
{
    public function __construct(
        private readonly TransactionRepository $transactions = new TransactionRepository(),
        private readonly PricingPlanRepository $plans = new PricingPlanRepository()
    ) {
    }

    public function initiate(Request $request): Response
    {
        $data = $request->all();
        $plan = null;
        foreach ($this->plans->active() as $candidate) {
            if ((int) $candidate['id'] === (int) $data['plan_id']) {
                $plan = $candidate;
                break;
            }
        }
        if (!$plan) {
            return Response::json(['message' => 'Plan not found'], 404);
        }
        $reference = bin2hex(random_bytes(8));
        $transactionId = $this->transactions->create([
            'user_id' => (int) $data['user_id'],
            'plan_id' => (int) $plan['id'],
            'amount' => (float) $plan['price'],
            'payment_method' => $data['payment_method'] ?? 'paystack',
            'reference' => $reference,
            'metadata' => ['callback' => $data['callback_url'] ?? null],
        ]);
        return Response::json(['message' => 'Payment initialized', 'data' => ['reference' => $reference, 'transaction_id' => $transactionId]]);
    }

    public function approveBankTransfer(Request $request, array $params): Response
    {
        $this->transactions->updateStatus((int) $params['transaction_id'], 'completed');
        return Response::json(['message' => 'Bank transfer approved']);
    }
}
