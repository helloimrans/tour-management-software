<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\User;
use App\Services\MemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    protected MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function showRegistrationForm()
    {
        return view('member.register');
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['nullable', 'string', 'max:191'],
                'phone' => [
                    'required',
                    'regex:/^(01[3-9]\d{8})$/',
                    'unique:users,phone',
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:191',
                    'unique:users,email',
                ],
                'profile_pic' => [
                    'nullable',
                    'mimes:jpg,jpeg,png,webp,svg,gif',
                    'max:5120',
                ],
                'password' => ['required', 'string', 'min:5', 'confirmed'],
                'address' => ['nullable', 'string', 'max:500'],
            ], [
                'first_name.required' => 'First name is required.',
                'first_name.max' => 'First name cannot exceed 191 characters.',
                'last_name.max' => 'Last name cannot exceed 191 characters.',
                'phone.required' => 'Phone number is required.',
                'phone.regex' => 'Please enter a valid phone number (01XXXXXXXXX).',
                'phone.unique' => 'This phone number is already registered.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'Email cannot exceed 191 characters.',
                'email.unique' => 'This email is already registered.',
                'profile_pic.mimes' => 'Profile picture must be an image file (jpg, jpeg, png, webp, svg, gif).',
                'profile_pic.max' => 'Profile picture size cannot exceed 5MB.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 5 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'address.max' => 'Address cannot exceed 500 characters.',
            ]);

            $user = $this->memberService->register($validatedData);

            return redirect()->route('login')->with([
                'message' => 'Registration successful! Your account is pending admin approval. You will be able to login once approved.',
                'alert-type' => 'info',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to register. Please try again.'])->withInput();
        }
    }

    public function dashboard()
    {
        $data = $this->memberService->getDashboardData(Auth::id());
        return view('member.dashboard', $data);
    }

    public function showProfile()
    {
        $data['user'] = Auth::user();
        return view('member.profile', $data);
    }

    public function updateProfile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:191'],
                'last_name' => ['nullable', 'string', 'max:191'],
                'phone' => [
                    'required',
                    'regex:/^(01[3-9]\d{8})$/',
                    'unique:users,phone,' . Auth::id(),
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:191',
                    'unique:users,email,' . Auth::id(),
                ],
                'profile_pic' => [
                    'nullable',
                    'mimes:jpg,jpeg,png,webp,svg,gif',
                    'max:5120',
                ],
                'address' => ['nullable', 'string', 'max:500'],
            ]);

            $this->memberService->updateProfile(Auth::id(), $validatedData);

            return redirect()->route('member.profile')->with([
                'message' => 'Profile updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update profile. Please try again.'])->withInput();
        }
    }

    public function tours()
    {
        $data['tours'] = $this->memberService->getAvailableTours();
        return view('member.tours', $data);
    }

    public function joinTour(Request $request, $tourId)
    {
        try {
            $this->memberService->joinTour(Auth::id(), $tourId);

            return redirect()->route('member.current-tour')->with([
                'message' => 'Successfully joined the tour!',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function currentTour()
    {
        $data = $this->memberService->getCurrentTour(Auth::id());
        return view('member.current-tour', $data);
    }

    public function tourHistory()
    {
        $data['tourMembers'] = $this->memberService->getTourHistory(Auth::id());
        return view('member.tour-history', $data);
    }

    public function showPaymentForm()
    {
        $data['tours'] = $this->memberService->getMemberTours(Auth::id());
        return view('member.add-payment', $data);
    }

    public function addPayment(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tour_id' => ['required', 'integer', 'exists:tours,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'payment_method' => ['required', 'string', 'in:cash,bank,bkash,nagad'],
                'transaction_number' => ['nullable', 'string', 'max:191'],
                'payment_date' => ['required', 'date'],
                'notes' => ['nullable', 'string'],
            ]);

            $this->memberService->addPayment(Auth::id(), $validatedData);

            return redirect()->route('member.payment-history')->with([
                'message' => 'Payment added successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function paymentHistory()
    {
        $data['payments'] = $this->memberService->getPaymentHistory(Auth::id());
        return view('member.payment-history', $data);
    }
}

