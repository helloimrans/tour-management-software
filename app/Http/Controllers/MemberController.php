<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function dashboard()
    {
        $data = [];
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

            $user = Auth::user();

            // Handle profile picture upload
            if ($request->hasFile('profile_pic')) {
                // Delete old profile pic if exists
                if ($user->profile_pic) {
                    Storage::disk('public')->delete($user->profile_pic);
                }

                $file = $request->file('profile_pic');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('profile_pics', $filename, 'public');
                $validatedData['profile_pic'] = $path;
            }

            // Update user
            $user->update([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'] ?? null,
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'] ?? null,
                'profile_pic' => $validatedData['profile_pic'] ?? $user->profile_pic,
                'address' => $validatedData['address'] ?? null,
            ]);

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
}

