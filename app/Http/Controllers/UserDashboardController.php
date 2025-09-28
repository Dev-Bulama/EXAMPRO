<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;
use App\Repositories\RewardRepository;
use App\Repositories\ScoreboardRepository;
use App\Repositories\SubscriptionRepository;
use App\Support\Request;
use App\Support\Response;

final class UserDashboardController
{
    public function __construct(
        private readonly ScoreboardRepository $scoreboard = new ScoreboardRepository(),
        private readonly RewardRepository $rewards = new RewardRepository(),
        private readonly SubscriptionRepository $subscriptions = new SubscriptionRepository(),
        private readonly NotificationRepository $notifications = new NotificationRepository()
    ) {
    }

    public function show(Request $request): Response
    {
        $user = $request->getAttribute('user');
        return Response::json([
            'data' => [
                'subscriptions' => $this->subscriptions->activeForUser((int) $user['id']),
                'rewards' => $this->rewards->totalForUser((int) $user['id']),
                'notifications' => $this->notifications->unread((int) $user['id']),
            ],
        ]);
    }
}
