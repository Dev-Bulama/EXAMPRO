<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\SettingRepository;
use App\Support\Request;
use App\Support\Response;

final class AdminSettingController
{
    public function __construct(private readonly SettingRepository $settings = new SettingRepository())
    {
    }

    public function update(Request $request): Response
    {
        foreach ($request->all() as $key => $value) {
            $this->settings->set($key, is_scalar($value) ? (string) $value : json_encode($value));
        }
        return Response::json(['message' => 'Settings saved']);
    }
}
