<?php

namespace App\Services;

use App\Mail\EmailVerificationMail;
use App\Mail\MagicLinkMail;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MagicLinkService
{
    /**
     * Generate a magic link token for a user.
     */
    public function generateMagicLink($user, $type = '2fa')
    {
        $token = Str::random(64);
        // Password setup links expire in 24 hours, others in 1 hour
        $expiresAt = $type === 'password-setup' ? now()->addHours(24) : now()->addHours(1);

        DB::table('magic_link_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'type' => $type,
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);

        $this->sendMagicLinkEmail($user, $token, $type, $expiresAt);

        return $token;
    }

    /**
     * Check if a magic link token is valid (without marking it as used).
     */
    public function isValidMagicLink($token, $type = '2fa')
    {
        $tokenRecord = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('type', $type)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        return $tokenRecord ? true : false;
    }

    /**
     * Verify a magic link token (marks it as used).
     */
    public function verifyMagicLink($token, $type = '2fa')
    {
        $tokenRecord = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('type', $type)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $tokenRecord) {
            return false;
        }

        // Mark token as used
        DB::table('magic_link_tokens')
            ->where('id', $tokenRecord->id)
            ->update(['used_at' => now()]);

        return $tokenRecord;
    }

    /**
     * Clean up expired tokens.
     */
    public function cleanupExpiredTokens()
    {
        return DB::table('magic_link_tokens')
            ->where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Get token statistics.
     */
    public function getTokenStats()
    {
        $now = now();

        return [
            'total' => DB::table('magic_link_tokens')->count(),
            'active' => DB::table('magic_link_tokens')
                ->whereNull('used_at')
                ->where('expires_at', '>', $now)
                ->count(),
            'expired' => DB::table('magic_link_tokens')
                ->where('expires_at', '<', $now)
                ->count(),
            'used' => DB::table('magic_link_tokens')
                ->whereNotNull('used_at')
                ->count(),
        ];
    }

    /**
     * Send magic link email.
     */
    private function sendMagicLinkEmail($user, $token, $type, $expiresAt)
    {
        \Log::info('Sending magic link email for type: '.$type);
        \Log::info('User email: '.$user->email);

        try {
            // For password-setup type, we handle it separately (no automatic email)
            // The email is sent manually in the controller with AdminWelcomeMail
            if ($type === 'password-setup') {
                \Log::info('Password setup link generated - email will be sent separately');

                return; // Don't send automatic email for password-setup
            }

            // For password_reset type with Admin models, email is sent manually to personal_email
            if ($type === 'password_reset' && $user instanceof \App\Models\Admin) {
                \Log::info('Admin password reset link generated - email will be sent separately to personal email');

                return; // Don't send automatic email for admin password reset
            }

            if ($type === 'password_reset') {
                $mail = new PasswordResetMail($user, $token, $expiresAt);
            } elseif ($type === 'email_verification') {
                $mail = new EmailVerificationMail($user, $token, $expiresAt);
            } else {
                $mail = new MagicLinkMail($user, $token, $expiresAt);
            }

            Mail::to($user->email)->send($mail);
            \Log::info('Magic link email sent successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to send magic link email');
            \Log::error('Error: '.$e->getMessage());
            \Log::error('File: '.$e->getFile().' Line: '.$e->getLine());

            throw $e;
        }
    }

    /**
     * Send 2FA enabled confirmation email.
     */
    public function sendTwoFactorEnabledConfirmation($user)
    {
        Mail::to($user->email)->send(new \App\Mail\TwoFactorEnabledMail($user));
    }
}
