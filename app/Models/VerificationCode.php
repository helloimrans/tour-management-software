<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VerificationCode extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function generateOTP($email, $is_resend = 0)
    {
        $randomOtp = rand(1000, 9999);

        return VerificationCode::create([
            'phone_or_email' => $email,
            'code' => $randomOtp,
            'is_verified' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'expired_at' => date('Y-m-d H:i:s', strtotime('+2 minutes')),
        ]);
    }


    public static function verifyOtp($phoneOrEmail, $code): void
    {
        $verificationCode = self::where('phone_or_email', '=', $phoneOrEmail)
            ->where('code', '=', $code)
            ->where('is_expired', '!=', 1)
            ->latest()
            ->first();

        if (! $verificationCode) {
            throw new BadRequestException(__('messages.otp_not_match'));
        }

        $minutes = (time() - strtotime($verificationCode->expired_at)) / 60;

        if ($verificationCode->is_expired || $minutes > 5) {
            $verificationCode->update(['is_expired' => 1]);

            throw new BadRequestException(__('messages.otp_expired'));
        }

        $verificationCode->update(['is_expired' => 1]);
    }

    public static function generateRandomPassword($length = 12)
    {
        // Characters to be included in the random password
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}[]|<>?';

        // Generate a random password by shuffling the characters and selecting the first $length characters
        $password = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }

        return $password;
    }
}
