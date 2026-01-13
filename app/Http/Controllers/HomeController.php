<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class HomeController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService->getDashboardData();
        return view('admin.dashboard', compact('data'));
    }
}
