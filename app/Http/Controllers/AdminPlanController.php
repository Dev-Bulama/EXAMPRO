<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PricingPlanRepository;
use App\Repositories\SubscriptionRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminPlanController
{
    public function __construct(
        private readonly PricingPlanRepository $plans = new PricingPlanRepository(),
        private readonly SubscriptionRepository $subscriptions = new SubscriptionRepository()
    ) {
    }

    public function storePlan(Request $request): Response
    {
        $planId = $this->plans->create($request->all());
        return Response::json(['message' => 'Plan created', 'data' => ['id' => $planId]], 201);
    }

    public function updatePlan(Request $request, array $params): Response
    {
        $this->plans->update((int) $params['id'], $request->all());
        return Response::json(['message' => 'Plan updated']);
    }

    public function assignUser(Request $request): Response
    {
        $data = $request->all();
        $this->subscriptions->create((int) $data['user_id'], (int) $data['plan_id'], (int) $data['duration_days']);
        return Response::json(['message' => 'User added to plan']);
    }
}
