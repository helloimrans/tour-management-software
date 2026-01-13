<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $data['upcomingTours'] = Tour::whereIn('status', ['upcoming', 'ongoing'])
            ->withCount(['tourMembers' => function($query) {
                $query->where('join_status', 'approved');
            }])
            ->latest()
            ->take(6)
            ->get();

        $data['featuredTour'] = Tour::whereIn('status', ['upcoming', 'ongoing'])
            ->withCount(['tourMembers' => function($query) {
                $query->where('join_status', 'approved');
            }])
            ->latest()
            ->first();

        return view('landing', $data);
    }

    public function tourListing(Request $request)
    {
        $query = Tour::query()
            ->withCount(['tourMembers' => function($q) {
                $q->where('join_status', 'approved');
            }]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'upcoming':
                $query->where('status', 'upcoming')->orderBy('start_date', 'asc');
                break;
            case 'price_low':
                $query->orderBy('per_member_cost', 'asc');
                break;
            case 'price_high':
                $query->orderBy('per_member_cost', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data['tours'] = $query->paginate(12);
        $data['search'] = $request->search;
        $data['status'] = $request->status;
        $data['sortBy'] = $sortBy;

        return view('tour-listing', $data);
    }
}

