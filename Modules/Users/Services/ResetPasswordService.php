<?php

namespace Modules\Users\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Users\Entities\User;
use Modules\Users\Notifications\UserResetPasswordNotification;

class ResetPasswordService
{
    /**
     * @param $email
     * @param $user_class
     *
     * @return bool
     * @throws \Exception
     */
    public function send($email)
    {
        $user_query = User::query();

        $user = $user_query->where("email", $email)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found.", 404);
        }

        $token = Str::random(60);

        $save_result = DB::table('password_resets')->insert([
            'email'      => $email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        if ($save_result) {
            $user->notify(new UserResetPasswordNotification($token));
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param $token
     * @param $user_class
     *
     * @return bool
     * @throws \Exception
     */
    public function check($email, $token)
    {
        /** @var \Illuminate\Support\Collection $data */
        $data = DB::table('password_resets')
            ->where("email", $email)
            ->get();

        if ($data->isNotEmpty()) {
            $result = $data->first(function ($value, $key) use ($token) {
                $is_token_checked = Hash::check($token, $value->token);
                $is_not_expired = Carbon::parse($value->created_at)
                    ->addMinutes(config("users.reset_password.expire", 60))
                    ->isFuture();
                return $is_not_expired && $is_token_checked;
            });

            if ($result) {
                return $result;
            }
        }

        return false;
    }

    /**
     *
     */
    public function setNewPassword($email, $token, $password)
    {
        $is_checked = $this->check($email, $token);

        if (!$is_checked) {
            return false;
        }
        $user_query = User::query();

        $user = $user_query->where("email", $email)->first();

        if (!$user) {
            throw new ModelNotFoundException("User not found.", 404);
        }

        $is_updated = $user->update([
            "password" => $password
        ]);

        if ($is_updated) {
            DB::table('password_resets')
                ->where("token", $is_checked->token)
                ->where("email", $email)
                ->delete();
        }

        return $is_updated;
    }
}
