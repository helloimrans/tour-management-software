<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\User;
use App\Models\TourMember;
use App\Models\Expense;

class DashboardService
{
    public function getDashboardData()
    {
        // Return default values since tour/expense functionality is disabled
        $totalTours = 0;
        $activeTours = 0;
        $totalParticipants = 0;
        $totalMembers = User::where('user_type', User::NORMAL_USER_CODE)->count();
        $totalExpenses = 0;

        return [
            'total_tours' => $totalTours,
            'active_tours' => $activeTours,
            'total_participants' => $totalParticipants,
            'total_members' => $totalMembers,
            'total_expenses' => $totalExpenses,
        ];
    }
}
