<?php

namespace App\Services;

use App\Models\Tour;
use App\Models\TourMember;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function register(array $input): User
    {
        if (isset($input['profile_pic'])) {
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        if (isset($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        }

        $input['user_type'] = User::NORMAL_USER_CODE;
        $input['status'] = 0; // Set to 0 (pending/inactive) - requires admin approval

        $user = User::create($input);

        // Ensure status is explicitly set to 0 (in case of any default override)
        $user->status = 0;
        $user->save();

        return $user->fresh();
    }

    public function updateProfile(int $userId, array $input): User
    {
        $user = User::findOrFail($userId);

        if (isset($input['profile_pic'])) {
            if ($user->profile_pic) {
                deleteFile($user->profile_pic);
            }
            $input['profile_pic'] = uploadFile($input['profile_pic'], 'profile_pic');
        }

        $input['updated_by'] = $userId;
        $user->update($input);

        return $user->fresh();
    }

    public function getDashboardData(int $userId)
    {
        $user = User::findOrFail($userId);

        // Get current active tour membership
        $currentTourMember = TourMember::where('user_id', $userId)
            ->whereIn('join_status', ['pending', 'approved'])
            ->with('tour')
            ->latest()
            ->first();

        // Count total tours joined
        $totalTours = TourMember::where('user_id', $userId)->count();

        // Get total payments made
        $totalPaid = Payment::where('user_id', $userId)->sum('amount');

        return [
            'user' => $user,
            'currentTourMember' => $currentTourMember,
            'currentTour' => $currentTourMember?->tour,
            'totalTours' => $totalTours,
            'totalPaid' => $totalPaid,
        ];
    }

    public function getAvailableTours()
    {
        return Tour::whereIn('status', ['upcoming', 'ongoing'])
            ->with('createdBy')
            ->withCount(['tourMembers' => function($query) {
                $query->where('join_status', 'approved');
            }])
            ->latest()
            ->get();
    }

    public function joinTour(int $userId, int $tourId)
    {
        $user = User::findOrFail($userId);
        $tour = Tour::findOrFail($tourId);

        if (!in_array($tour->status, ['upcoming', 'ongoing'])) {
            throw new \Exception('This tour is not available for joining.');
        }

        // Check if already joined
        $existingMembership = TourMember::where('user_id', $userId)
            ->where('tour_id', $tourId)
            ->whereIn('join_status', ['pending', 'approved'])
            ->first();

        if ($existingMembership) {
            throw new \Exception('You have already joined this tour.');
        }

        // Check if tour is full
        $approvedMembers = TourMember::where('tour_id', $tourId)
            ->where('join_status', 'approved')
            ->count();

        if ($approvedMembers >= $tour->max_members) {
            throw new \Exception('This tour is full.');
        }

        // Create tour membership
        $tourMember = TourMember::create([
            'tour_id' => $tourId,
            'user_id' => $userId,
            'join_status' => 'pending',
            'joined_at' => now(),
            'created_by' => $userId,
        ]);

        return $tourMember;
    }

    public function getCurrentTour(int $userId)
    {
        $user = User::findOrFail($userId);

        $tourMember = TourMember::where('user_id', $userId)
            ->whereIn('join_status', ['pending', 'approved'])
            ->with(['tour.schedules', 'tour.tourMembers' => function($query) {
                $query->where('join_status', 'approved')->with('user');
            }])
            ->latest()
            ->first();

        // Get payment summary for current tour
        $paymentSummary = null;
        if ($tourMember) {
            $totalPaid = Payment::where('user_id', $userId)
                ->where('tour_id', $tourMember->tour_id)
                ->sum('amount');

            $remaining = $tourMember->tour->per_member_cost - $totalPaid;

            $paymentSummary = [
                'total_cost' => $tourMember->tour->per_member_cost,
                'total_paid' => $totalPaid,
                'remaining' => max(0, $remaining),
            ];
        }

        return [
            'user' => $user,
            'tourMember' => $tourMember,
            'tour' => $tourMember?->tour,
            'paymentSummary' => $paymentSummary,
        ];
    }

    public function getTourHistory(int $userId)
    {
        return TourMember::where('user_id', $userId)
            ->with(['tour', 'tour.createdBy'])
            ->orderBy('joined_at', 'desc')
            ->get();
    }

    public function addPayment(int $userId, array $input)
    {
        // Verify tour membership
        $tourMember = TourMember::where('user_id', $userId)
            ->where('tour_id', $input['tour_id'])
            ->whereIn('join_status', ['pending', 'approved'])
            ->firstOrFail();

        $input['user_id'] = $userId;
        $input['created_by'] = $userId;

        return Payment::create($input);
    }

    public function getPaymentHistory(int $userId)
    {
        return Payment::where('user_id', $userId)
            ->with('tour')
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    public function getMemberTours(int $userId)
    {
        return TourMember::where('user_id', $userId)
            ->whereIn('join_status', ['pending', 'approved'])
            ->with('tour')
            ->get();
    }
}
