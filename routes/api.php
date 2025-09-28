<?php

declare(strict_types=1);

use App\Http\Controllers\AdminExamController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\UserDashboardController;
use App\Support\Auth;
use App\Support\Request;
use App\Support\Router;
use App\Support\Response;

$router = new Router();
$authController = new AuthController();
$examController = new ExamController();
$adminExamController = new AdminExamController();
$adminPlanController = new AdminPlanController();
$adminResourceController = new AdminResourceController();
$adminSettingController = new AdminSettingController();
$adminUserController = new AdminUserController();
$paymentController = new PaymentController();
$resourceController = new ResourceController();
$userDashboardController = new UserDashboardController();

$router->add('GET', '/', function (): Response {
    ob_start();
    include BASE_PATH . '/resources/views/landing.php';
    $html = ob_get_clean();
    return new Response(200, ['Content-Type' => 'text/html; charset=UTF-8'], $html);
});

$authMiddleware = fn(Request $request, array $params, callable $next) => Auth::authenticate($request, $params, $next);

$router->add('POST', '/api/register', fn(Request $request) => $authController->register($request));
$router->add('POST', '/api/login', fn(Request $request) => $authController->login($request));
$router->add('POST', '/api/logout', fn(Request $request) => $authController->logout($request), [$authMiddleware]);

$router->add('GET', '/api/exams', fn(Request $request) => $examController->index());
$router->add('GET', '/api/exams/{id}', fn(Request $request, array $params) => $examController->show($request, $params));
$router->add('POST', '/api/exams/{id}/start', fn(Request $request, array $params) => $examController->start($request, $params), [$authMiddleware]);
$router->add('POST', '/api/attempts/{attempt_id}/submit', fn(Request $request, array $params) => $examController->submit($request, $params), [$authMiddleware]);
$router->add('POST', '/api/attempts/{attempt_id}/complete', fn(Request $request, array $params) => $examController->finish($request, $params), [$authMiddleware]);
$router->add('GET', '/api/exams/{exam_id}/leaderboard', fn(Request $request, array $params) => $examController->leaderboard($request, $params));

$router->add('GET', '/api/exams/{exam_id}/resources', fn(Request $request, array $params) => $resourceController->forExam($request, $params), [$authMiddleware]);
$router->add('GET', '/api/dashboard', fn(Request $request) => $userDashboardController->show($request), [$authMiddleware]);

$router->add('POST', '/api/payments/initiate', fn(Request $request) => $paymentController->initiate($request), [$authMiddleware]);
$router->add('POST', '/api/payments/{transaction_id}/approve', fn(Request $request, array $params) => $paymentController->approveBankTransfer($request, $params), [$authMiddleware, Auth::requireRole('admin')]);

$router->add('GET', '/api/admin/dashboard', fn(Request $request) => $adminExamController->dashboard(), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('POST', '/api/admin/exams', fn(Request $request) => $adminExamController->storeExam($request), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('PUT', '/api/admin/exams/{id}', fn(Request $request, array $params) => $adminExamController->updateExam($request, $params), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('DELETE', '/api/admin/exams/{id}', fn(Request $request, array $params) => $adminExamController->deleteExam($request, $params), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('POST', '/api/admin/sections/{section_id}/questions', fn(Request $request, array $params) => $adminExamController->addQuestion($request, $params), [$authMiddleware, Auth::requireRole('admin')]);

$router->add('POST', '/api/admin/plans', fn(Request $request) => $adminPlanController->storePlan($request), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('PUT', '/api/admin/plans/{id}', fn(Request $request, array $params) => $adminPlanController->updatePlan($request, $params), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('POST', '/api/admin/plans/assign', fn(Request $request) => $adminPlanController->assignUser($request), [$authMiddleware, Auth::requireRole('admin')]);

$router->add('POST', '/api/admin/resources', fn(Request $request) => $adminResourceController->store($request), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('DELETE', '/api/admin/resources/{id}', fn(Request $request, array $params) => $adminResourceController->destroy($request, $params), [$authMiddleware, Auth::requireRole('admin')]);

$router->add('POST', '/api/admin/settings', fn(Request $request) => $adminSettingController->update($request), [$authMiddleware, Auth::requireRole('admin')]);

$router->add('GET', '/api/admin/users', fn(Request $request) => $adminUserController->index(), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('PUT', '/api/admin/users/{id}', fn(Request $request, array $params) => $adminUserController->update($request, $params), [$authMiddleware, Auth::requireRole('admin')]);
$router->add('DELETE', '/api/admin/users/{id}', fn(Request $request, array $params) => $adminUserController->destroy($request, $params), [$authMiddleware, Auth::requireRole('admin')]);

return $router;
