<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendSmsEvent;
use App\Http\Controllers\Controller;
use App\Services\SMSService;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    private SMSService $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function showRegisterForm()
    {
        if (auth()->check()) {
            return redirect()->route('sme.form');
        } else {
            return view('auth.register');
        }
    }

    public function showForgotPassword(): View
    {
        return \view('forgot-password.show-forgot-password-form');
    }

    public function showResetPassword($token): View
    {
        return \view('forgot-password.reset-password-form', ['token' => $token]);
    }

    public function forgotPasswordOtpReceiveValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'phone' => 'numeric|required|regex:/^(01[3-9]\d{8})$/',
            'code' => 'required|max:4|min:4',
        ]);
    }

    public function updateResetPasswordValidator(array $data)
    {
        $rules = [
            'password' => 'min:6|required|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'token' => 'required'
        ];
        $validatedData = Validator::make($data, $rules)->validate();

        return $validatedData;
    }


    public function forgotPasswordOtpSendValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'phone' => 'numeric|required|regex:/^(01[3-9]\d{8})$/',
        ]);
    }

    public function forgotPasswordOtpSend(Request $request): JsonResponse
    {
        try {
            $validateData = $this->forgotPasswordOtpSendValidator($request)->validate();

            $user = User::where('phone', '=', $validateData['phone'])->first();

            if (!$user) {
                return response()->json(['message' => 'Your phone number is not registered, please sign up.', 'alert-type' => 'error', 'success' => false]);
            }

            $existingOtps = VerificationCode::where('phone_or_email', $validateData['phone'])
                ->where('expired_at', '>=', Carbon::now())
                ->where('is_expired', 0)
                ->count();


            if ($existingOtps) {

                return response()->json(['message' => 'Please try again after 2 minutes.', 'alert-type' => 'error', 'success' => false]);
            }

            $otp = VerificationCode::generateOTP($validateData['phone']);
            $msg = 'আপনার ওটিপিঃ ' . $otp->code;

            if (env('APP_ENV') == 'local') {
                return response()->json(['message' => __('messages.otp_sent_successfully'), 'otp' => $otp, 'alert-type' => 'success', 'success' => true]);
            }

            event(new SendSmsEvent($otp->phone_or_email, $msg));

            return response()->json(['message' => __('messages.otp_sent_successfully'), 'alert-type' => 'success', 'success' => true]);
        } catch (ValidationException $exception) {
            return response()->json(['message' => $exception->validator->errors()->first(), 'alert-type' => 'error', 'success' => false]);
        } catch (BadRequestException $exception) {
            return response()->json(['message' => $exception->getMessage(), 'alert-type' => 'error', 'success' => false]);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('messages.something_wrong_try_again'), 'alert-type' => 'error', 'success' => false]);
        }
    }

    public function forgotPasswordOtpVerify(Request $request)
    {
        try {
            $validateData = $this->forgotPasswordOtpReceiveValidator($request)->validate();
            VerificationCode::verifyOtp($validateData['phone'], $validateData['code']);

            $token = Str::random(64);

            DB::table('password_reset_tokens')->insert([
                'email' => $request->phone,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            return response()->json(['message' => __('messages.otp_verified_successfully'), 'alert-type' => 'success', 'success' => true, 'token' => $token],);
        } catch (ValidationException $exception) {
            return response()->json(['message' => $exception->validator->errors()->first(), 'alert-type' => 'error', 'success' => false]);
        } catch (BadRequestException $exception) {
            return response()->json(['message' => $exception->getMessage(), 'alert-type' => 'error', 'success' => false]);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('messages.something_wrong_try_again'), 'alert-type' => 'error', 'success' => false]);
        }
    }

    public function updateResetPassword(Request $request)
    {

        try {

            $validateData =  $this->updateResetPasswordValidator($request->all());

            $isToken = DB::table('password_reset_tokens')->where(['token' => $validateData['token']])->first();
            if (!$isToken) {
                $notification = array(
                    'message' => 'Invalid Token.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            //Check token time
            $tokenCreatedAt = Carbon::parse($isToken->created_at);
            $expiryTime = $tokenCreatedAt->addMinutes(5);
            $currentTime = Carbon::now();
            if (!$currentTime->lt($expiryTime)) {
                $notification = array(
                    'message' => 'Token has expired.',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            $phone = $isToken->email;
            User::where('phone', $phone)
                ->update(['password' => Hash::make($validateData['password'])]);

            DB::table('password_reset_tokens')->where(['email' => $phone])->delete();

            $notification = array(
                'message' => 'Successfully password changed.',
                'alert-type' => 'success'
            );

            return redirect('/login')->with($notification);

        } catch (ValidationException $exception) {
            return back()->withErrors($exception->validator)->withInput();
        } catch (\Throwable $exception) {
            return back()->with([
                'message' => __('messages.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }
    }
}
